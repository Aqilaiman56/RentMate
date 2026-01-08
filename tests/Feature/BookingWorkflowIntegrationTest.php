<?php

use App\Models\User;
use App\Models\Item;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Deposit;
use App\Models\Category;
use App\Models\Location;
use App\Models\Notification;
use App\Services\ToyyibPayService;
use Illuminate\Support\Facades\Http;

use function Pest\Laravel\{actingAs, post, get, assertDatabaseHas, assertDatabaseMissing};

describe('Complete Booking Workflow Integration', function () {

    beforeEach(function () {
        // Mock ToyyibPay service
        Http::fake([
            'toyyibpay.com/*' => Http::response([
                'success' => true,
                'billCode' => 'TEST-BILL-123',
                'paymentUrl' => 'https://toyyibpay.com/test'
            ], 200)
        ]);
    });

    test('user can complete full booking workflow from creation to completion', function () {
        // Arrange: Create test data
        $owner = User::factory()->create(['Email' => 'owner@test.com']);
        $renter = User::factory()->create(['Email' => 'renter@test.com']);
        $category = Category::factory()->create(['CategoryName' => 'Electronics']);
        $location = Location::factory()->create(['LocationName' => 'Kuala Lumpur']);

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'ItemName' => 'Camera',
            'PricePerDay' => 50.00,
            'DepositAmount' => 200.00,
            'Quantity' => 2,
            'AvailableQuantity' => 2,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
            'Availability' => true,
        ]);

        // Step 1: Renter creates booking
        $response = actingAs($renter)->post('/bookings/create-and-pay', [
            'ItemID' => $item->ItemID,
            'StartDate' => now()->addDays(5)->format('Y-m-d'),
            'EndDate' => now()->addDays(10)->format('Y-m-d'),
            'Quantity' => 1,
        ]);

        $response->assertRedirect();

        // Verify booking created
        assertDatabaseHas('booking', [
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'Status' => 'Pending',
        ]);

        $booking = Booking::where('UserID', $renter->UserID)->first();
        expect($booking)->not->toBeNull();
        expect($booking->TotalAmount)->toBeGreaterThan(0);

        // Verify deposit created
        assertDatabaseHas('deposits', [
            'BookingID' => $booking->BookingID,
            'DepositAmount' => 200.00,
            'Status' => 'Held',
        ]);

        // Step 2: Simulate payment callback (successful)
        $payment = Payment::factory()->create([
            'BookingID' => $booking->BookingID,
            'BillCode' => 'TEST-BILL-123',
            'Amount' => $booking->TotalAmount,
            'Status' => 'Pending',
        ]);

        $callbackResponse = post('/payment/callback', [
            'billcode' => 'TEST-BILL-123',
            'status_id' => '1', // Success
            'transaction_id' => 'TXN-123456',
            'order_id' => (string)$booking->BookingID,
        ]);

        // Verify payment successful
        $payment->refresh();
        expect($payment->Status)->toBe('Successful');
        expect($payment->TransactionID)->toBe('TXN-123456');

        // Step 3: Owner approves booking
        $approveResponse = actingAs($owner)->post("/booking/{$booking->BookingID}/approve");

        $booking->refresh();
        expect($booking->Status)->toBe('Approved');

        // Verify item quantity updated
        $item->refresh();
        expect($item->AvailableQuantity)->toBe(1); // 2 - 1 booked

        // Verify notification sent to renter
        assertDatabaseHas('notifications', [
            'UserID' => $renter->UserID,
            'Type' => 'booking',
        ]);

        // Step 4: Owner marks booking as complete
        $booking->update(['Status' => 'Ongoing']);

        $completeResponse = actingAs($owner)->post("/booking/{$booking->BookingID}/complete");

        $booking->refresh();
        expect($booking->Status)->toBe('Completed');
        expect($booking->ReturnConfirmed)->toBeTrue();

        // Verify deposit eligible for refund
        $deposit = $booking->deposit;
        expect($deposit->Status)->toBe('Held');

        // Step 5: Verify refund queue entry created
        assertDatabaseHas('refund_queue', [
            'BookingID' => $booking->BookingID,
            'UserID' => $renter->UserID,
            'Status' => 'Pending',
        ]);

        // Verify completion notification sent
        assertDatabaseHas('notifications', [
            'UserID' => $renter->UserID,
            'Type' => 'booking',
        ]);
    });

    test('booking fails when dates overlap with existing booking at full capacity', function () {
        $owner = User::factory()->create();
        $renter1 = User::factory()->create();
        $renter2 = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'Quantity' => 1,
            'AvailableQuantity' => 1,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Create first booking
        $booking1 = Booking::factory()->create([
            'UserID' => $renter1->UserID,
            'ItemID' => $item->ItemID,
            'StartDate' => now()->addDays(5),
            'EndDate' => now()->addDays(10),
            'Status' => 'Approved',
            'Quantity' => 1,
        ]);

        // Attempt to create overlapping booking
        $response = actingAs($renter2)->post('/bookings/store', [
            'ItemID' => $item->ItemID,
            'StartDate' => now()->addDays(7)->format('Y-m-d'),
            'EndDate' => now()->addDays(12)->format('Y-m-d'),
            'Quantity' => 1,
        ]);

        // Should fail or redirect with error
        $response->assertSessionHasErrors('dates');

        // Verify second booking not created
        $bookingCount = Booking::where('ItemID', $item->ItemID)->count();
        expect($bookingCount)->toBe(1);
    });

    test('owner can reject booking and trigger refund', function () {
        $owner = User::factory()->create();
        $renter = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'Status' => 'Pending',
            'StartDate' => now()->addDays(5),
            'EndDate' => now()->addDays(10),
        ]);

        $deposit = Deposit::factory()->create([
            'BookingID' => $booking->BookingID,
            'Status' => 'Held',
            'DepositAmount' => 200.00,
        ]);

        // Payment completed
        Payment::factory()->create([
            'BookingID' => $booking->BookingID,
            'Status' => 'Successful',
            'Amount' => 300.00,
        ]);

        // Owner rejects booking
        $response = actingAs($owner)->post("/booking/{$booking->BookingID}/reject", [
            'rejection_reason' => 'Item no longer available'
        ]);

        $booking->refresh();
        expect($booking->Status)->toBe('Rejected');

        // Verify refund queue entry created
        assertDatabaseHas('refund_queue', [
            'BookingID' => $booking->BookingID,
            'UserID' => $renter->UserID,
            'Status' => 'Pending',
        ]);

        // Verify notification sent
        assertDatabaseHas('notifications', [
            'UserID' => $renter->UserID,
            'Type' => 'booking',
        ]);
    });

    test('renter can cancel pending booking before approval', function () {
        $owner = User::factory()->create();
        $renter = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'Status' => 'Pending',
            'StartDate' => now()->addDays(5),
            'EndDate' => now()->addDays(10),
        ]);

        // Renter cancels booking
        $response = actingAs($renter)->post("/booking/{$booking->BookingID}/cancel");

        $booking->refresh();
        expect($booking->Status)->toBe('Cancelled');

        // Verify notification sent to owner
        assertDatabaseHas('notifications', [
            'UserID' => $owner->UserID,
            'Type' => 'booking',
        ]);
    });

    test('user cannot book their own item', function () {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $user->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Attempt to book own item
        $response = actingAs($user)->post('/bookings/store', [
            'ItemID' => $item->ItemID,
            'StartDate' => now()->addDays(5)->format('Y-m-d'),
            'EndDate' => now()->addDays(10)->format('Y-m-d'),
            'Quantity' => 1,
        ]);

        $response->assertSessionHasErrors();

        // Verify booking not created
        assertDatabaseMissing('booking', [
            'UserID' => $user->UserID,
            'ItemID' => $item->ItemID,
        ]);
    });

    test('multi-quantity item allows partial booking', function () {
        $owner = User::factory()->create();
        $renter1 = User::factory()->create();
        $renter2 = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'Quantity' => 3,
            'AvailableQuantity' => 3,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // First booking - 1 quantity
        $booking1 = Booking::factory()->create([
            'UserID' => $renter1->UserID,
            'ItemID' => $item->ItemID,
            'StartDate' => now()->addDays(5),
            'EndDate' => now()->addDays(10),
            'Status' => 'Approved',
            'Quantity' => 1,
        ]);

        // Update available quantity
        $item->updateAvailableQuantity();
        $item->refresh();

        expect($item->AvailableQuantity)->toBe(2);

        // Second booking - 1 quantity (should succeed)
        $booking2 = Booking::factory()->create([
            'UserID' => $renter2->UserID,
            'ItemID' => $item->ItemID,
            'StartDate' => now()->addDays(6),
            'EndDate' => now()->addDays(11),
            'Status' => 'Pending',
            'Quantity' => 1,
        ]);

        expect($booking2)->not->toBeNull();

        // Verify availability check
        $isAvailable = $item->isAvailableForDates(
            now()->addDays(6)->format('Y-m-d'),
            now()->addDays(11)->format('Y-m-d')
        );

        expect($isAvailable)->toBeTrue(); // Still 1 quantity available
    });

    test('booking calculates service fee correctly', function () {
        $owner = User::factory()->create();
        $renter = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'PricePerDay' => 100.00,
            'DepositAmount' => 300.00,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'StartDate' => now()->addDays(5),
            'EndDate' => now()->addDays(10), // 5 days
            'Status' => 'Pending',
        ]);

        // Service fee should be RM1.00 (hardcoded in system)
        expect($booking->ServiceFeeAmount)->toBe('1.00');

        // Total should be: (PricePerDay * Days) + Deposit + ServiceFee
        // (100 * 5) + 300 + 1 = 801
        $expectedTotal = 801.00;
        expect((float)$booking->TotalAmount)->toBe($expectedTotal);
    });
});
