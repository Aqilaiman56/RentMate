<?php

use App\Services\ToyyibPayService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;

beforeEach(function () {
    // Set up test configuration
    config([
        'toyyibpay.api_url' => 'https://dev.toyyibpay.com/',
        'toyyibpay.secret_key' => 'test-secret-key',
        'toyyibpay.category_code' => 'test-category',
        'toyyibpay.callback_url' => 'http://localhost/payment/callback',
        'toyyibpay.test_mode' => false,
    ]);
});

test('toyyibpay service can be instantiated', function () {
    $service = new ToyyibPayService();

    expect($service)->toBeInstanceOf(ToyyibPayService::class);
});

test('create bill in test mode returns test bill code', function () {
    config(['toyyibpay.test_mode' => true]);

    $service = new ToyyibPayService();
    $bookingData = [
        'booking_id' => 123,
        'bill_name' => 'Test Booking',
        'bill_description' => 'Test Description',
        'amount' => 100.00,
        'payer_name' => 'John Doe',
        'payer_email' => 'john@example.com',
    ];

    $result = $service->createBill($bookingData);

    expect($result)->toBeArray()
        ->and($result['success'])->toBeTrue()
        ->and($result['bill_code'])->toContain('TEST-')
        ->and($result['payment_url'])->toContain('payment/test');
});

test('create bill with successful api response returns bill code', function () {
    // Mock the Guzzle client
    $mockClient = Mockery::mock(Client::class);
    $mockResponse = new Response(200, [], json_encode([
        [
            'BillCode' => 'abc123xyz',
            'BillName' => 'Test Booking',
        ]
    ]));

    $mockClient->shouldReceive('post')
        ->once()
        ->andReturn($mockResponse);

    // Create service with mocked client
    $service = new ToyyibPayService();
    $reflection = new ReflectionClass($service);
    $property = $reflection->getProperty('client');
    $property->setAccessible(true);
    $property->setValue($service, $mockClient);

    $bookingData = [
        'booking_id' => 123,
        'bill_name' => 'Test Booking',
        'bill_description' => 'Test Description',
        'amount' => 100.00,
        'payer_name' => 'John Doe',
        'payer_email' => 'john@example.com',
    ];

    $result = $service->createBill($bookingData);

    expect($result)->toBeArray()
        ->and($result['success'])->toBeTrue()
        ->and($result['bill_code'])->toBe('abc123xyz')
        ->and($result['payment_url'])->toContain('abc123xyz');
});

test('create bill with failed api response returns error', function () {
    // Mock the Guzzle client with failed response
    $mockClient = Mockery::mock(Client::class);
    $mockResponse = new Response(200, [], json_encode([
        'error' => 'Invalid parameters'
    ]));

    $mockClient->shouldReceive('post')
        ->once()
        ->andReturn($mockResponse);

    $service = new ToyyibPayService();
    $reflection = new ReflectionClass($service);
    $property = $reflection->getProperty('client');
    $property->setAccessible(true);
    $property->setValue($service, $mockClient);

    $bookingData = [
        'booking_id' => 123,
        'bill_name' => 'Test Booking',
        'bill_description' => 'Test Description',
        'amount' => 100.00,
        'payer_name' => 'John Doe',
        'payer_email' => 'john@example.com',
    ];

    $result = $service->createBill($bookingData);

    expect($result)->toBeArray()
        ->and($result['success'])->toBeFalse()
        ->and($result['message'])->toBe('Failed to create bill');
});

test('create bill with exception returns error', function () {
    // Mock the Guzzle client to throw exception
    $mockClient = Mockery::mock(Client::class);
    $mockClient->shouldReceive('post')
        ->once()
        ->andThrow(new \Exception('Connection timeout'));

    $service = new ToyyibPayService();
    $reflection = new ReflectionClass($service);
    $property = $reflection->getProperty('client');
    $property->setAccessible(true);
    $property->setValue($service, $mockClient);

    $bookingData = [
        'booking_id' => 123,
        'bill_name' => 'Test Booking',
        'bill_description' => 'Test Description',
        'amount' => 100.00,
        'payer_name' => 'John Doe',
        'payer_email' => 'john@example.com',
    ];

    $result = $service->createBill($bookingData);

    expect($result)->toBeArray()
        ->and($result['success'])->toBeFalse()
        ->and($result['message'])->toBe('Connection timeout');
});

test('get bill transactions returns transactions data', function () {
    $mockClient = Mockery::mock(Client::class);
    $mockResponse = new Response(200, [], json_encode([
        [
            'billCode' => 'abc123',
            'billpaymentStatus' => '1',
            'billpaymentAmount' => '100.00',
        ]
    ]));

    $mockClient->shouldReceive('post')
        ->once()
        ->andReturn($mockResponse);

    $service = new ToyyibPayService();
    $reflection = new ReflectionClass($service);
    $property = $reflection->getProperty('client');
    $property->setAccessible(true);
    $property->setValue($service, $mockClient);

    $result = $service->getBillTransactions('abc123');

    expect($result)->toBeArray()
        ->and($result[0]['billCode'])->toBe('abc123');
});

test('get bill transactions with exception returns null', function () {
    $mockClient = Mockery::mock(Client::class);
    $mockClient->shouldReceive('post')
        ->once()
        ->andThrow(new \Exception('API Error'));

    $service = new ToyyibPayService();
    $reflection = new ReflectionClass($service);
    $property = $reflection->getProperty('client');
    $property->setAccessible(true);
    $property->setValue($service, $mockClient);

    $result = $service->getBillTransactions('abc123');

    expect($result)->toBeNull();
});

afterEach(function () {
    Mockery::close();
});
