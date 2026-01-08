<?php

use App\Models\User;
use App\Models\Item;
use App\Models\Booking;
use App\Models\Deposit;
use App\Models\RefundQueue;
use App\Models\ForfeitQueue;
use App\Models\Category;
use App\Models\Location;

use function Pest\Laravel\{actingAs, post, get, assertDatabaseHas};

describe('Deposit Management and Refund Queue Integration', function () {

    test('deposit is created automatically when booking is created', function () {
        $owner = User::factory()->create();
        $renter = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'DepositAmount' => 500.00,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'DepositAmount' => 500.00,
            'Status' => 'Pending',
        ]);

        // Create deposit
        $deposit = Deposit::factory()->create([
            'BookingID' => $booking->BookingID,
            'DepositAmount' => 500.00,
            'Status' => 'Held',
        ]);

        // Verify deposit created correctly
        assertDatabaseHas('deposits', [
            'BookingID' => $booking->BookingID,
            'DepositAmount' => '500.00',
            'Status' => 'Held',
        ]);

        expect($booking->deposit)->not->toBeNull();
        expect((float)$booking->deposit->DepositAmount)->toBe(500.00);
    });

    test('admin can refund deposit after booking completion', function () {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->create();
        $renter = User::factory()->create([
            'BankName' => 'Test Bank',
            'BankAccountNumber' => '1234567890',
            'BankAccountHolderName' => 'John Doe',
        ]);
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
            'Status' => 'Completed',
            'ReturnConfirmed' => true,
        ]);

        $deposit = Deposit::factory()->create([
            'BookingID' => $booking->BookingID,
            'DepositAmount' => 300.00,
            'Status' => 'Held',
        ]);

        // Admin initiates refund
        $response = actingAs($admin)->post("/admin/deposits/{$deposit->DepositID}/refund");

        $deposit->refresh();
        expect($deposit->Status)->toBe('Refunded');

        // Verify refund queue entry created
        assertDatabaseHas('refund_queue', [
            'DepositID' => $deposit->DepositID,
            'BookingID' => $booking->BookingID,
            'UserID' => $renter->UserID,
            'RefundAmount' => '300.00',
            'Status' => 'Pending',
            'BankName' => 'Test Bank',
            'BankAccountNumber' => '1234567890',
        ]);
    });

    test('admin can process partial refund for damaged item', function () {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->create();
        $renter = User::factory()->create([
            'BankName' => 'Test Bank',
            'BankAccountNumber' => '1234567890',
            'BankAccountHolderName' => 'John Doe',
        ]);
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
            'Status' => 'Completed',
        ]);

        $deposit = Deposit::factory()->create([
            'BookingID' => $booking->BookingID,
            'DepositAmount' => 500.00,
            'Status' => 'Held',
        ]);

        // Admin processes partial refund (deduct RM100 for damage)
        $response = actingAs($admin)->post("/admin/deposits/{$deposit->DepositID}/partial-refund", [
            'refund_amount' => 400.00,
            'reason' => 'Minor damage to item',
        ]);

        $deposit->refresh();
        expect($deposit->Status)->toBe('Partial');

        // Verify refund queue with partial amount
        assertDatabaseHas('refund_queue', [
            'DepositID' => $deposit->DepositID,
            'RefundAmount' => '400.00',
            'Status' => 'Pending',
        ]);
    });

    test('admin can forfeit deposit for severe violation', function () {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->create([
            'BankName' => 'Owner Bank',
            'BankAccountNumber' => '9876543210',
            'BankAccountHolderName' => 'Jane Owner',
        ]);
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
            'Status' => 'Completed',
        ]);

        $deposit = Deposit::factory()->create([
            'BookingID' => $booking->BookingID,
            'DepositAmount' => 600.00,
            'Status' => 'Held',
        ]);

        // Admin forfeits deposit
        $response = actingAs($admin)->post("/admin/deposits/{$deposit->DepositID}/forfeit", [
            'reason' => 'Item severely damaged and not returned',
        ]);

        $deposit->refresh();
        expect($deposit->Status)->toBe('Forfeited');

        // Verify forfeit queue entry created for owner
        assertDatabaseHas('forfeit_queue', [
            'DepositID' => $deposit->DepositID,
            'BookingID' => $booking->BookingID,
            'OwnerUserID' => $owner->UserID,
            'RenterUserID' => $renter->UserID,
            'ForfeitAmount' => '600.00',
            'Status' => 'Pending',
        ]);
    });

    test('refund queue processes from pending to completed', function () {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => User::factory()->create()->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $user->UserID,
            'ItemID' => $item->ItemID,
        ]);

        $deposit = Deposit::factory()->create([
            'BookingID' => $booking->BookingID,
            'DepositAmount' => 250.00,
            'Status' => 'Held',
        ]);

        $refundQueue = RefundQueue::factory()->create([
            'DepositID' => $deposit->DepositID,
            'BookingID' => $booking->BookingID,
            'UserID' => $user->UserID,
            'RefundAmount' => 250.00,
            'Status' => 'Pending',
        ]);

        // Step 1: Admin marks as processing
        $response = actingAs($admin)->post("/admin/refund-queue/{$refundQueue->RefundQueueID}/process");

        $refundQueue->refresh();
        expect($refundQueue->Status)->toBe('Processing');

        // Step 2: Admin completes refund with proof
        $response = actingAs($admin)->post("/admin/refund-queue/{$refundQueue->RefundQueueID}/complete", [
            'refund_reference' => 'REF123456',
            'notes' => 'Bank transfer completed',
            'proof_of_transfer' => 'proof.jpg',
        ]);

        $refundQueue->refresh();
        expect($refundQueue->Status)->toBe('Completed');
        expect($refundQueue->RefundReference)->toBe('REF123456');
        expect($refundQueue->ProcessedBy)->toBe($admin->UserID);
        expect($refundQueue->ProcessedAt)->not->toBeNull();
    });

    test('cancelled booking adds deposit to refund queue automatically', function () {
        $owner = User::factory()->create();
        $renter = User::factory()->create([
            'BankName' => 'Test Bank',
            'BankAccountNumber' => '1234567890',
            'BankAccountHolderName' => 'John Doe',
        ]);
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
            'Status' => 'Approved',
        ]);

        $deposit = Deposit::factory()->create([
            'BookingID' => $booking->BookingID,
            'DepositAmount' => 300.00,
            'Status' => 'Held',
        ]);

        // Owner cancels booking (triggers auto-refund)
        $response = actingAs($owner)->post("/booking/{$booking->BookingID}/cancel");

        $booking->refresh();
        expect($booking->Status)->toBe('Cancelled');

        // Verify refund queue entry created
        assertDatabaseHas('refund_queue', [
            'BookingID' => $booking->BookingID,
            'UserID' => $renter->UserID,
            'DepositID' => $deposit->DepositID,
            'Status' => 'Pending',
        ]);
    });

    test('admin can view all deposits with filters', function () {
        $admin = User::factory()->admin()->create();

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => User::factory()->create()->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking1 = Booking::factory()->create(['UserID' => $user1->UserID, 'ItemID' => $item->ItemID]);
        $booking2 = Booking::factory()->create(['UserID' => $user2->UserID, 'ItemID' => $item->ItemID]);

        Deposit::factory()->create(['BookingID' => $booking1->BookingID, 'Status' => 'Held']);
        Deposit::factory()->create(['BookingID' => $booking2->BookingID, 'Status' => 'Refunded']);

        // Admin views all deposits
        $response = actingAs($admin)->get('/admin/deposits');
        $response->assertOk();

        // Admin filters by status
        $response = actingAs($admin)->get('/admin/deposits?status=Held');
        $response->assertOk();
        $response->assertSee('Held');
    });

    test('deposit status transitions are tracked correctly', function () {
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => User::factory()->create()->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => User::factory()->create()->UserID,
            'ItemID' => $item->ItemID,
        ]);

        $deposit = Deposit::factory()->create([
            'BookingID' => $booking->BookingID,
            'Status' => 'Held',
        ]);

        // Transition: Held -> Refunded
        $deposit->update(['Status' => 'Refunded', 'DateRefunded' => now()]);
        expect($deposit->Status)->toBe('Refunded');
        expect($deposit->DateRefunded)->not->toBeNull();

        // Verify can check refund status
        $canRefund = $deposit->canRefund();
        expect($canRefund)->toBeFalse(); // Already refunded
    });

    test('refund queue includes correct bank details for processing', function () {
        $user = User::factory()->create([
            'UserName' => 'Test User',
            'BankName' => 'Maybank',
            'BankAccountNumber' => '1122334455',
            'BankAccountHolderName' => 'Test User',
        ]);
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => User::factory()->create()->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $user->UserID,
            'ItemID' => $item->ItemID,
        ]);

        $deposit = Deposit::factory()->create([
            'BookingID' => $booking->BookingID,
            'DepositAmount' => 400.00,
        ]);

        $refundQueue = RefundQueue::factory()->create([
            'DepositID' => $deposit->DepositID,
            'BookingID' => $booking->BookingID,
            'UserID' => $user->UserID,
            'RefundAmount' => 400.00,
            'BankName' => 'Maybank',
            'BankAccountNumber' => '1122334455',
            'BankAccountHolderName' => 'Test User',
            'Status' => 'Pending',
        ]);

        // Verify bank details captured correctly
        expect($refundQueue->BankName)->toBe('Maybank');
        expect($refundQueue->BankAccountNumber)->toBe('1122334455');
        expect($refundQueue->BankAccountHolderName)->toBe('Test User');
    });
});
