<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\ToyyibPayService;

echo "Testing ToyyibPay Service...\n";

try {
    $service = new ToyyibPayService();

    $billData = [
        'bill_name' => 'Test Deposit',
        'bill_description' => 'Test deposit payment',
        'amount' => 50.00,
        'booking_id' => 'TEST-123',
        'payer_name' => 'aqil',
        'payer_email' => 'mhdaqilaiman@gmail.com',
        'payer_phone' => '0123456789'
    ];

    $result = $service->createBill($billData);

    echo "Result:\n";
    var_dump($result);

    if (isset($result['success']) && $result['success']) {
        echo "\n✅ Bill created successfully!\n";
        echo "Bill Code: " . $result['bill_code'] . "\n";
        echo "Payment URL: " . $result['payment_url'] . "\n";
    } else {
        echo "\n❌ Failed to create bill\n";
        echo "Error: " . ($result['message'] ?? 'Unknown error') . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
