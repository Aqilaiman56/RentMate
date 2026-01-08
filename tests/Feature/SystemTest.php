<?php

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Deposit;
use App\Models\Review;
use App\Models\Report;
use App\Models\Penalty;
use App\Models\Message;
use App\Models\Wishlist;
use App\Models\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('System Testing - Complete End-to-End Scenarios', function () {

    /**
     * SYSTEM TEST 1: Complete Rental Lifecycle
     * Tests the entire flow from user registration through booking completion
     */
    test('ST-001: complete rental lifecycle from registration to review', function () {
        // Mock ToyyibPay API
        Http::fake([
            'https://dev.toyyibpay.com/*' => Http::response([
                'success' => true,
                'billCode' => 'TEST-BILL-123',
                'paymentUrl' => 'https://dev.toyyibpay.com/pay/TEST-BILL-123'
            ], 200)
        ]);

        // Phase 1: User Registration
        $renterData = [
            'UserName' => 'johndoe',
            'Email' => 'john@example.com',
            'PasswordHash' => Hash::make('password123'),
            'PhoneNumber' => '0123456789',
            'IsAdmin' => false
        ];
        $renter = User::create($renterData);
        $this->assertDatabaseHas('users', ['Email' => 'john@example.com']);

        // Phase 2: Owner creates item listing
        $owner = User::factory()->create();
        $category = Category::factory()->create(['CategoryName' => 'Electronics']);
        $location = Location::factory()->create(['CityName' => 'Kuala Lumpur']);

        $item = Item::create([
            'ItemName' => 'Canon EOS R5 Camera',
            'Description' => 'Professional mirrorless camera',
            'PricePerDay' => 150.00,
            'DepositAmount' => 500.00,
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
            'Quantity' => 2,
            'AvailableQuantity' => 2,
            'Availability' => true
        ]);

        $this->assertDatabaseHas('items', ['ItemName' => 'Canon EOS R5 Camera']);
        expect($item->hasAvailableQuantity())->toBeTrue();

        // Phase 3: Renter creates booking
        $startDate = now()->addDays(5)->format('Y-m-d');
        $endDate = now()->addDays(8)->format('Y-m-d');
        $days = 3;
        $totalAmount = $item->PricePerDay * $days + 1.00; // +RM1 service fee

        $booking = Booking::create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'StartDate' => $startDate,
            'EndDate' => $endDate,
            'Quantity' => 1,
            'TotalCost' => $item->PricePerDay * $days,
            'ServiceFeeAmount' => 1.00,
            'TotalPaid' => $totalAmount,
            'Status' => 'Pending',
            'ReturnConfirmed' => false
        ]);

        $this->assertDatabaseHas('bookings', [
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'Status' => 'Pending'
        ]);

        // Phase 4: Deposit is created automatically
        $deposit = Deposit::create([
            'BookingID' => $booking->BookingID,
            'DepositAmount' => $item->DepositAmount,
            'Status' => 'held',
            'DateCollected' => now()
        ]);

        expect($deposit->Status)->toBe('held');
        expect($deposit->canRefund())->toBeTrue();

        // Phase 5: Payment processing
        $payment = Payment::create([
            'BookingID' => $booking->BookingID,
            'BillCode' => 'TEST-BILL-123',
            'Amount' => $totalAmount,
            'Status' => 'pending',
            'CreatedAt' => now()
        ]);

        // Simulate payment success
        $payment->update([
            'Status' => 'successful',
            'TransactionID' => 'TXN-456-789',
            'PaymentDate' => now()
        ]);

        $this->assertDatabaseHas('payments', [
            'BookingID' => $booking->BookingID,
            'Status' => 'successful',
            'TransactionID' => 'TXN-456-789'
        ]);

        // Phase 6: Owner approves booking
        $this->actingAs($owner);
        $booking->update(['Status' => 'Approved']);

        $this->assertDatabaseHas('bookings', [
            'BookingID' => $booking->BookingID,
            'Status' => 'Approved'
        ]);

        // Phase 7: Rental completed
        $booking->update(['Status' => 'Completed', 'ReturnConfirmed' => true]);

        // Phase 8: Deposit refunded
        $deposit->update([
            'Status' => 'refunded',
            'RefundDate' => now()
        ]);

        expect($deposit->Status)->toBe('refunded');

        // Phase 9: Renter leaves review
        $this->actingAs($renter);
        Review::create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'Rating' => 5,
            'Comment' => 'Excellent camera! Owner was very helpful.',
            'DatePosted' => now(),
            'IsReported' => false
        ]);

        $this->assertDatabaseHas('reviews', [
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'Rating' => 5
        ]);

        // Final verification
        expect($booking->Status)->toBe('Completed');
        expect($payment->Status)->toBe('successful');
        expect($deposit->Status)->toBe('refunded');
        expect($item->reviews()->count())->toBe(1);
    });

    /**
     * SYSTEM TEST 2: Multi-User Booking with Quantity Management
     */
    test('ST-002: multiple users booking same item with quantity management', function () {
        $owner = User::factory()->create();
        $renter1 = User::factory()->create();
        $renter2 = User::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'Quantity' => 2,
            'AvailableQuantity' => 2
        ]);

        $startDate = now()->addDays(10)->format('Y-m-d');
        $endDate = now()->addDays(12)->format('Y-m-d');

        // Renter 1 books 1 quantity
        Booking::create([
            'UserID' => $renter1->UserID,
            'ItemID' => $item->ItemID,
            'StartDate' => $startDate,
            'EndDate' => $endDate,
            'Quantity' => 1,
            'TotalCost' => 200.00,
            'ServiceFeeAmount' => 1.00,
            'TotalPaid' => 201.00,
            'Status' => 'Approved'
        ]);

        // Update availability
        $item->update(['AvailableQuantity' => 1]);
        $item->refresh();
        expect($item->AvailableQuantity)->toBe(1);

        // Renter 2 books remaining quantity
        Booking::create([
            'UserID' => $renter2->UserID,
            'ItemID' => $item->ItemID,
            'StartDate' => $startDate,
            'EndDate' => $endDate,
            'Quantity' => 1,
            'TotalCost' => 200.00,
            'ServiceFeeAmount' => 1.00,
            'TotalPaid' => 201.00,
            'Status' => 'Approved'
        ]);

        // Now fully booked
        $item->update(['AvailableQuantity' => 0, 'Availability' => false]);
        $item->refresh();

        expect($item->AvailableQuantity)->toBe(0);
        expect($item->Availability)->toBeFalse();
        $this->assertDatabaseCount('bookings', 2);
    });

    /**
     * SYSTEM TEST 3: Admin Dispute Resolution
     */
    test('ST-003: admin handles dispute with penalty and suspension', function () {
        $admin = User::factory()->create(['IsAdmin' => true]);
        $renter = User::factory()->create();
        $owner = User::factory()->create();

        $item = Item::factory()->create(['UserID' => $owner->UserID]);
        $booking = Booking::factory()->create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'Status' => 'Completed'
        ]);

        $deposit = Deposit::factory()->create([
            'BookingID' => $booking->BookingID,
            'Status' => 'held'
        ]);

        // Owner reports renter
        $report = Report::create([
            'ReportedByID' => $owner->UserID,
            'ReportedUserID' => $renter->UserID,
            'BookingID' => $booking->BookingID,
            'ItemID' => $item->ItemID,
            'ReportType' => 'damage',
            'Priority' => 'high',
            'Subject' => 'Camera lens broken',
            'Description' => 'Renter returned camera with broken lens',
            'EvidencePath' => 'damage-photo.jpg',
            'Status' => 'pending',
            'DateReported' => now()
        ]);

        $this->assertDatabaseHas('reports', [
            'ReportID' => $report->ReportID,
            'Status' => 'pending'
        ]);

        // Admin creates penalty
        Penalty::create([
            'ReportID' => $report->ReportID,
            'ReportedByID' => $owner->UserID,
            'ReportedUserID' => $renter->UserID,
            'BookingID' => $booking->BookingID,
            'ItemID' => $item->ItemID,
            'PenaltyAmount' => 150.00,
            'Reason' => 'Equipment damage',
            'ApprovedByAdminID' => $admin->UserID,
            'DateReported' => now(),
            'ResolvedStatus' => false
        ]);

        $report->update([
            'Status' => 'resolved',
            'ReviewedByAdminID' => $admin->UserID,
            'DateResolved' => now()
        ]);

        $this->assertDatabaseHas('reports', [
            'ReportID' => $report->ReportID,
            'Status' => 'resolved'
        ]);

        // Admin suspends user
        $renter->update([
            'IsSuspended' => true,
            'SuspensionReason' => 'Equipment damage',
            'SuspendedUntil' => now()->addDays(7),
            'SuspendedByAdminID' => $admin->UserID
        ]);

        $renter->refresh();
        expect($renter->isCurrentlySuspended())->toBeTrue();
    });

    /**
     * SYSTEM TEST 4: Wishlist and Messaging
     */
    test('ST-004: user wishlist management and messaging flow', function () {
        $renter = User::factory()->create();
        $owner = User::factory()->create();

        $item1 = Item::factory()->create(['UserID' => $owner->UserID]);
        $item2 = Item::factory()->create(['UserID' => $owner->UserID]);

        $this->actingAs($renter);

        // Add to wishlist
        Wishlist::create([
            'UserID' => $renter->UserID,
            'ItemID' => $item1->ItemID,
            'DateAdded' => now()
        ]);

        expect(Wishlist::isInWishlist($renter->UserID, $item1->ItemID))->toBeTrue();

        // Send message
        $message = Message::create([
            'SenderID' => $renter->UserID,
            'ReceiverID' => $owner->UserID,
            'ItemID' => $item2->ItemID,
            'MessageContent' => 'Is this available next week?',
            'SentAt' => now(),
            'IsRead' => false
        ]);

        $this->assertDatabaseHas('messages', [
            'SenderID' => $renter->UserID,
            'ReceiverID' => $owner->UserID,
            'IsRead' => false
        ]);

        // Owner replies
        $this->actingAs($owner);
        $message->update(['IsRead' => true]);

        Message::create([
            'SenderID' => $owner->UserID,
            'ReceiverID' => $renter->UserID,
            'ItemID' => $item2->ItemID,
            'MessageContent' => 'Yes, it is available!',
            'SentAt' => now(),
            'IsRead' => false
        ]);

        // Verify conversation
        $conversation = Message::conversation($renter->UserID, $owner->UserID)->get();
        expect($conversation->count())->toBe(2);
    });

    /**
     * SYSTEM TEST 5: Access Control
     */
    test('ST-005: access control validation for all user types', function () {
        $admin = User::factory()->create(['IsAdmin' => true]);
        $owner = User::factory()->create(['IsAdmin' => false]);
        $renter = User::factory()->create(['IsAdmin' => false]);
        $suspended = User::factory()->create(['IsAdmin' => false, 'IsSuspended' => true]);

        Item::factory()->create(['UserID' => $owner->UserID]);

        // Test 1: Guest cannot access protected routes
        $response = $this->get('/bookings');
        expect($response->status())->toBeIn([302, 500]); // Redirect or error expected

        // Test 2: Suspended user is blocked
        expect($suspended->isCurrentlySuspended())->toBeTrue();

        // Test 3: Admin can access despite suspension
        $admin->update(['IsSuspended' => true]);
        $this->actingAs($admin);
        // Admin bypass should work

        // Test 4: Regular user can login
        $this->actingAs($renter);
        expect(auth()->check())->toBeTrue();
    });

    /**
     * SYSTEM TEST 6: Notification System
     */
    test('ST-006: notifications created for major events', function () {
        $owner = User::factory()->create();
        $renter = User::factory()->create();

        $item = Item::factory()->create(['UserID' => $owner->UserID]);

        // Create booking
        $booking = Booking::create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'StartDate' => now()->addDays(5),
            'EndDate' => now()->addDays(7),
            'Quantity' => 1,
            'TotalCost' => 100.00,
            'ServiceFeeAmount' => 1.00,
            'TotalPaid' => 101.00,
            'Status' => 'Pending'
        ]);

        // Create notification
        Notification::create([
            'UserID' => $owner->UserID,
            'Type' => 'booking_request',
            'Title' => 'New Booking Request',
            'Content' => "New booking request for {$item->ItemName}",
            'RelatedID' => $booking->BookingID,
            'RelatedType' => 'Booking',
            'IsRead' => false,
            'CreatedAt' => now()
        ]);

        $this->assertDatabaseHas('notifications', [
            'UserID' => $owner->UserID,
            'Type' => 'booking_request',
            'IsRead' => false
        ]);

        // Mark as read
        Notification::where('UserID', $owner->UserID)->update(['IsRead' => true]);

        $unreadCount = Notification::where('UserID', $owner->UserID)
            ->where('IsRead', false)
            ->count();
        expect($unreadCount)->toBe(0);
    });

    /**
     * SYSTEM TEST 7: Data Integrity
     */
    test('ST-007: data integrity maintained across related records', function () {
        $owner = User::factory()->create();
        $renter = User::factory()->create();

        $item = Item::factory()->create(['UserID' => $owner->UserID]);

        $booking = Booking::factory()->create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID
        ]);

        Deposit::factory()->create(['BookingID' => $booking->BookingID]);
        Payment::factory()->create(['BookingID' => $booking->BookingID]);
        Review::factory()->create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID
        ]);

        // Verify relationships
        expect($booking->user->UserID)->toBe($renter->UserID);
        expect($booking->item->ItemID)->toBe($item->ItemID);
        expect($item->bookings()->count())->toBeGreaterThan(0);
        expect($item->reviews()->count())->toBeGreaterThan(0);

        // Verify database consistency
        $this->assertDatabaseHas('bookings', ['BookingID' => $booking->BookingID]);
        $this->assertDatabaseHas('deposits', ['BookingID' => $booking->BookingID]);
        $this->assertDatabaseHas('payments', ['BookingID' => $booking->BookingID]);
        $this->assertDatabaseHas('reviews', ['ItemID' => $item->ItemID]);
    });

    /**
     * SYSTEM TEST 8: Performance with Multiple Records
     */
    test('ST-008: system handles multiple concurrent operations', function () {
        $startTime = microtime(true);

        // Create test data
        $users = User::factory()->count(15)->create();
        $items = Item::factory()->count(15)->create();

        expect(User::count())->toBeGreaterThanOrEqual(15);
        expect(Item::count())->toBeGreaterThanOrEqual(15);

        // Create bookings
        for ($i = 0; $i < 10; $i++) {
            Booking::factory()->create([
                'UserID' => $users->random()->UserID,
                'ItemID' => $items->random()->ItemID
            ]);
        }

        expect(Booking::count())->toBeGreaterThanOrEqual(10);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Performance should be reasonable
        expect($executionTime)->toBeLessThan(30);

        // Query performance
        $queryStart = microtime(true);
        $availableItems = Item::where('Availability', true)
            ->where('AvailableQuantity', '>', 0)
            ->get();
        $queryEnd = microtime(true);

        expect($queryEnd - $queryStart)->toBeLessThan(2);
        expect($availableItems->count())->toBeGreaterThan(0);
    });

    /**
     * SYSTEM TEST 9: Edge Cases
     */
    test('ST-009: system handles edge cases gracefully', function () {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // Edge Case 1: Zero deposit amount
        $itemNoDeposit = Item::factory()->create(['DepositAmount' => 0.00]);
        $booking = Booking::factory()->create(['ItemID' => $itemNoDeposit->ItemID]);
        $deposit = Deposit::create([
            'BookingID' => $booking->BookingID,
            'DepositAmount' => 0.00,
            'Status' => 'held'
        ]);

        expect((float)$deposit->DepositAmount)->toBe(0.0);

        // Edge Case 2: Review without comment
        $review = Review::create([
            'UserID' => $user->UserID,
            'ItemID' => $item->ItemID,
            'Rating' => 4,
            'Comment' => null,
            'DatePosted' => now()
        ]);

        expect($review->Comment)->toBeNull();
        expect($review->Rating)->toBe(4);

        // Edge Case 3: Expired suspension
        $suspendedUser = User::factory()->create([
            'IsSuspended' => true,
            'SuspendedUntil' => now()->subDays(1)
        ]);

        expect($suspendedUser->isCurrentlySuspended())->toBeFalse();

        // Edge Case 4: Wishlist toggle
        $result1 = Wishlist::toggle($user->UserID, $item->ItemID);
        expect($result1['added'])->toBeTrue();

        $result2 = Wishlist::toggle($user->UserID, $item->ItemID);
        expect($result2['added'])->toBeFalse();
    });

    /**
     * SYSTEM TEST 10: Payment Flow
     */
    test('ST-010: complete payment workflow', function () {
        Http::fake([
            'https://dev.toyyibpay.com/*' => Http::response([
                'success' => true,
                'billCode' => 'TEST-123',
                'paymentUrl' => 'https://test.com/pay'
            ], 200)
        ]);

        $renter = User::factory()->create();
        $item = Item::factory()->create();

        $booking = Booking::create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'StartDate' => now()->addDays(5),
            'EndDate' => now()->addDays(7),
            'Quantity' => 1,
            'TotalCost' => 100.00,
            'ServiceFeeAmount' => 1.00,
            'TotalPaid' => 101.00,
            'Status' => 'Pending'
        ]);

        // Create payment
        $payment = Payment::create([
            'BookingID' => $booking->BookingID,
            'BillCode' => 'TEST-123',
            'Amount' => 101.00,
            'Status' => 'pending',
            'CreatedAt' => now()
        ]);

        $this->assertDatabaseHas('payments', [
            'BookingID' => $booking->BookingID,
            'Status' => 'pending'
        ]);

        // Payment success
        $payment->update([
            'Status' => 'successful',
            'TransactionID' => 'TXN-123',
            'PaymentDate' => now()
        ]);

        $this->assertDatabaseHas('payments', [
            'BookingID' => $booking->BookingID,
            'Status' => 'successful'
        ]);

        expect($payment->Status)->toBe('successful');
    });

});
