<?php

return [
    'api_url' => rtrim(env('TOYYIBPAY_BASE_URL', 'https://dev.toyyibpay.com'), '/') . '/',
    'secret_key' => env('TOYYIBPAY_SECRET_KEY'),
    'category_code' => env('TOYYIBPAY_CATEGORY_CODE'),
    'callback_url' => env('APP_URL') . '/payment/callback',
    'test_mode' => env('TOYYIBPAY_TEST_MODE', false),
];
