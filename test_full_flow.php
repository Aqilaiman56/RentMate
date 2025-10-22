<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Item;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\ToyyibPayService;
use Carbon\Carbon;

echo "Testing Full Payment Flow...\n\n";

try {
    // Get test data
    $user = User::where('Email', 'mhdaqilaiman@gmail.com')->first();
    $item = Item::where('ItemName', 'Canon EOS R5 Camera')->first();

    if (!$user || !$item) {
        echo "❌ Test user or item not found\n";
        exit(1);
    }

    echo "Using test user: {$user->UserName} ({$user->Email})\n";
    echo "Using test item: {$item->ItemName} (Deposit: RM{$item->DepositAmount})\n\n";

    // Step 1: Create a booking
    echo "=== STEP 1: Creating Booking ===\n";

    $startDate = Carbon::now()->addDays(1);
    $endDate = Carbon::now()->addDays(3);

    $booking = Booking::create([
        'UserID' => $user->UserID,
        'ItemID' => $item->ItemID,
        'StartDate' => $startDate,
        'EndDate' => $endDate,
        'TotalAmount' => $item->PricePerDay * 3, // 3 days
        'DepositAmount' => $item->DepositAmount,
        'Status' => 'pending',
        'BookingDate' => now()
    ]);

    echo "✅ Booking created with ID: {$booking->BookingID}\n\n";

    // Step 2: Create payment record
    echo "=== STEP 2: Creating Payment Record ===\n";

    $payment = Payment::create([
        'BookingID' => $booking->BookingID,
        'Amount' => $booking->DepositAmount + 1.00, // Deposit + Tax
        'Status' => 'pending',
        'CreatedAt' => now()
    ]);

    echo "✅ Payment record created with ID: {$payment->PaymentID}\n\n";

    // Step 3: Create ToyyibPay bill
    echo "=== STEP 3: Creating ToyyibPay Bill ===\n";

    $toyyibpay = new ToyyibPayService();

    $billData = [
        'booking_id' => $booking->BookingID,
        'bill_name' => 'Security Deposit - Booking #' . $booking->BookingID,
        'bill_description' => 'Security deposit for ' . $item->ItemName,
        'amount' => $payment->Amount,
        'payer_name' => $user->UserName,
        'payer_email' => $user->Email,
        'payer_phone' => $user->PhoneNumber ?? '',
    ];

    $result = $toyyibpay->createBill($billData);

    if ($result['success']) {
        // Update payment with bill code
        $payment->update(['BillCode' => $result['bill_code']]);

        echo "✅ ToyyibPay bill created successfully!\n";
        echo "Bill Code: {$result['bill_code']}\n";
        echo "Payment URL: {$result['payment_url']}\n\n";

        // Step 4: Simulate successful payment callback
        echo "=== STEP 4: Simulating Payment Callback ===\n";

        // Simulate successful payment callback data
        $callbackData = [
            'billcode' => $result['bill_code'],
            'status_id' => 1, // 1 = Successful
            'transaction_id' => 'TEST-' . time(),
            'order_id' => 'ORDER-' . time()
        ];

        // Process callback (simulate what PaymentController does)
        $payment->update([
            'Status' => 'successful',
            'TransactionID' => $callbackData['transaction_id'],
            'PaymentMethod' => 'ToyyibPay - FPX',
            'PaymentDate' => now(),
            'PaymentResponse' => json_encode($callbackData)
        ]);

        $booking->update(['Status' => 'confirmed']);

        echo "✅ Payment callback processed successfully!\n";
        echo "Payment Status: {$payment->Status}\n";
        echo "Booking Status: {$booking->Status}\n\n";

        echo "🎉 Full payment flow test completed successfully!\n";

    } else {
        echo "❌ Failed to create ToyyibPay bill: " . ($result['message'] ?? 'Unknown error') . "\n";
    }

} catch (Exception $e) {
    echo "❌ Error during testing: " . $e->getMessage() . "\n";
}
