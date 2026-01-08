<?php

use App\Models\User;
use App\Models\Item;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Category;
use App\Models\Location;
use App\Services\ToyyibPayService;
use Illuminate\Support\Facades\Http;

use function Pest\Laravel\{actingAs, post, get, assertDatabaseHas};

describe('Payment Processing Integration', function () {

    test('payment creation generates ToyyibPay bill successfully', function () {
        // Mock HTTP response from ToyyibPay
        Http::fake([
            'https://toyyibpay.com/index.php/api/createBill' => Http::response([
                'BillCode' => 'ABC123XYZ',
            ], 200)
        ]);

        $user = User::factory()->create();
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'PricePerDay' => 50.00,
            'DepositAmount' => 200.00,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $user->UserID,
            'ItemID' => $item->ItemID,
            'TotalAmount' => 350.00,
            'Status' => 'Pending',
        ]);

        // Create payment
        $response = actingAs($user)->post('/payment/create', [
            'BookingID' => $booking->BookingID,
        ]);

        // Verify payment record created
        assertDatabaseHas('payments', [
            'BookingID' => $booking->BookingID,
            'Status' => 'Pending',
        ]);

        $payment = Payment::where('BookingID', $booking->BookingID)->first();
        expect($payment->BillCode)->not->toBeNull();
        expect($payment->Amount)->toBe('350.00');
    });

    test('payment callback handles successful payment', function () {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $user->UserID,
            'ItemID' => $item->ItemID,
            'TotalAmount' => 350.00,
            'Status' => 'Pending',
            'TotalPaid' => 0,
        ]);

        $payment = Payment::factory()->create([
            'BookingID' => $booking->BookingID,
            'BillCode' => 'BILL123',
            'Amount' => 350.00,
            'Status' => 'Pending',
        ]);

        // Simulate ToyyibPay callback - Success
        $response = post('/payment/callback', [
            'billcode' => 'BILL123',
            'status_id' => '1', // 1 = Successful
            'transaction_id' => 'TXN456789',
            'order_id' => (string)$booking->BookingID,
            'msg' => 'Payment successful',
        ]);

        // Verify payment status updated
        $payment->refresh();
        expect($payment->Status)->toBe('Successful');
        expect($payment->TransactionID)->toBe('TXN456789');
        expect($payment->PaymentDate)->not->toBeNull();

        // Verify booking updated
        $booking->refresh();
        expect((float)$booking->TotalPaid)->toBe(350.00);

        // Verify notification created
        assertDatabaseHas('notifications', [
            'UserID' => $user->UserID,
            'Type' => 'payment',
        ]);
    });

    test('payment callback handles failed payment', function () {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $user->UserID,
            'ItemID' => $item->ItemID,
            'TotalAmount' => 350.00,
            'Status' => 'Pending',
        ]);

        $payment = Payment::factory()->create([
            'BookingID' => $booking->BookingID,
            'BillCode' => 'BILL456',
            'Amount' => 350.00,
            'Status' => 'Pending',
        ]);

        // Simulate ToyyibPay callback - Failed
        $response = post('/payment/callback', [
            'billcode' => 'BILL456',
            'status_id' => '3', // 3 = Failed
            'transaction_id' => '',
            'order_id' => (string)$booking->BookingID,
            'msg' => 'Insufficient funds',
        ]);

        // Verify payment status updated to failed
        $payment->refresh();
        expect($payment->Status)->toBe('Failed');

        // Verify booking remains pending
        $booking->refresh();
        expect($booking->Status)->toBe('Pending');
        expect((float)$booking->TotalPaid)->toBe(0.00);
    });

    test('payment callback handles pending payment status', function () {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $user->UserID,
            'ItemID' => $item->ItemID,
            'TotalAmount' => 350.00,
            'Status' => 'Pending',
        ]);

        $payment = Payment::factory()->create([
            'BookingID' => $booking->BookingID,
            'BillCode' => 'BILL789',
            'Amount' => 350.00,
            'Status' => 'Pending',
        ]);

        // Simulate ToyyibPay callback - Pending
        $response = post('/payment/callback', [
            'billcode' => 'BILL789',
            'status_id' => '2', // 2 = Pending
            'transaction_id' => '',
            'order_id' => (string)$booking->BookingID,
            'msg' => 'Payment processing',
        ]);

        // Verify payment remains pending
        $payment->refresh();
        expect($payment->Status)->toBe('Pending');
    });

    test('test mode creates mock payment without API call', function () {
        config(['services.toyyibpay.test_mode' => true]);

        $service = new ToyyibPayService();

        $billData = [
            'amount' => 350.00,
            'description' => 'Test Payment',
            'billName' => 'RentMate Booking',
            'billEmail' => 'test@example.com',
        ];

        $result = $service->createBill($billData);

        expect($result['success'])->toBeTrue();
        expect($result['billCode'])->toContain('TEST-');
        expect($result['paymentUrl'])->not->toBeNull();
    });

    test('payment status can be checked by user', function () {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $user->UserID,
            'ItemID' => $item->ItemID,
        ]);

        $payment = Payment::factory()->create([
            'BookingID' => $booking->BookingID,
            'BillCode' => 'CHECK123',
            'Amount' => 350.00,
            'Status' => 'Successful',
            'TransactionID' => 'TXN999',
        ]);

        // User checks payment status
        $response = actingAs($user)->get("/payment/{$payment->PaymentID}");

        $response->assertOk();
        $response->assertSee('Successful');
        $response->assertSee('TXN999');
    });

    test('multiple payment attempts create separate records', function () {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $user->UserID,
            'ItemID' => $item->ItemID,
            'TotalAmount' => 350.00,
            'Status' => 'Pending',
        ]);

        // First payment attempt fails
        $payment1 = Payment::factory()->create([
            'BookingID' => $booking->BookingID,
            'BillCode' => 'BILL001',
            'Status' => 'Failed',
        ]);

        // Second payment attempt succeeds
        $payment2 = Payment::factory()->create([
            'BookingID' => $booking->BookingID,
            'BillCode' => 'BILL002',
            'Status' => 'Successful',
            'TransactionID' => 'TXN123',
        ]);

        // Verify both payment records exist
        $payments = Payment::where('BookingID', $booking->BookingID)->get();
        expect($payments->count())->toBe(2);

        // Verify booking updated with successful payment
        $successfulPayment = $payments->where('Status', 'Successful')->first();
        expect($successfulPayment)->not->toBeNull();
        expect($successfulPayment->TransactionID)->toBe('TXN123');
    });

    test('payment amount must match booking total', function () {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $user->UserID,
            'ItemID' => $item->ItemID,
            'TotalAmount' => 350.00,
        ]);

        $payment = Payment::factory()->create([
            'BookingID' => $booking->BookingID,
            'Amount' => 350.00,
        ]);

        expect((float)$payment->Amount)->toBe((float)$booking->TotalAmount);
    });
});
