<?php

return [
    'secret_key' => env('TOYYIBPAY_SECRET_KEY', ''),
    'category_code' => env('TOYYIBPAY_CATEGORY_CODE', ''),
    'sandbox_mode' => env('TOYYIBPAY_SANDBOX', true),

    // API URLs
    'api_url' => env('TOYYIBPAY_SANDBOX', true)
        ? 'https://dev.toyyibpay.com/'
        : 'https://toyyibpay.com/',

    // Callback URL for testing (use ngrok URL in production)
    'callback_url' => env('TOYYIBPAY_CALLBACK_URL', 'https://irrevocable-tinkly-clemmie.ngrok-free.dev/payment/callback'),
];
