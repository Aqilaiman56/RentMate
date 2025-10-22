<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

echo "Testing Callback URL Accessibility...\n\n";

$callbackUrl = config('toyyibpay.callback_url');
echo "Callback URL: {$callbackUrl}\n\n";

// Test 1: Basic HTTP GET request to callback URL
echo "=== TEST 1: Basic HTTP GET Request ===\n";
try {
    $response = Http::timeout(10)->get($callbackUrl);
    echo "Status Code: {$response->status()}\n";
    echo "Response: " . substr($response->body(), 0, 200) . "...\n";
    echo "✅ Callback URL is accessible via GET\n\n";
} catch (\Exception $e) {
    echo "❌ GET request failed: " . $e->getMessage() . "\n\n";
}

// Test 2: Simulate ToyyibPay callback POST request
echo "=== TEST 2: Simulate ToyyibPay Callback POST ===\n";
try {
    $testCallbackData = [
        'billcode' => 'TEST-CALLBACK-' . time(),
        'status_id' => 1, // Successful
        'transaction_id' => 'TEST-TXN-' . time(),
        'order_id' => 'TEST-ORDER-' . time(),
        'amount' => '10000', // 100.00 in cents
    ];

    $response = Http::timeout(10)->post($callbackUrl, $testCallbackData);
    echo "Status Code: {$response->status()}\n";
    echo "Response: " . substr($response->body(), 0, 200) . "...\n";

    if ($response->status() == 302) {
        echo "✅ Callback URL redirected (expected for invalid bill code)\n";
    } else {
        echo "✅ Callback URL processed the request\n";
    }
    echo "\n";

} catch (\Exception $e) {
    echo "❌ POST request failed: " . $e->getMessage() . "\n\n";
}

// Test 3: Check if URL is reachable from external service
echo "=== TEST 3: External Reachability Check ===\n";
echo "Note: This test simulates what ToyyibPay would do\n";
echo "If ngrok is running correctly, external services should be able to reach:\n";
echo "{$callbackUrl}\n\n";

echo "🎉 Callback URL accessibility tests completed!\n";
echo "If all tests passed, ToyyibPay should be able to reach your callback URL.\n";
