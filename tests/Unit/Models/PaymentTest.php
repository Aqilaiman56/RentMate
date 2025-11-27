<?php

use App\Models\Payment;
use App\Models\Booking;

test('payment can be created with valid data', function () {
    $booking = Booking::factory()->create();

    $payment = Payment::factory()->create([
        'BookingID' => $booking->BookingID,
        'BillCode' => 'BILL1234',
        'Amount' => 350.00,
        'Status' => 'pending',
    ]);

    expect($payment)->toBeInstanceOf(Payment::class)
        ->and($payment->BookingID)->toBe($booking->BookingID)
        ->and($payment->BillCode)->toBe('BILL1234')
        ->and($payment->Amount)->toBe('350.00')
        ->and($payment->Status)->toBe('pending');
});

test('payment belongs to booking', function () {
    $booking = Booking::factory()->create();
    $payment = Payment::factory()->create(['BookingID' => $booking->BookingID]);

    expect($payment->booking)->toBeInstanceOf(Booking::class)
        ->and($payment->booking->BookingID)->toBe($booking->BookingID);
});

test('payment can be marked as successful', function () {
    $payment = Payment::factory()->successful()->create();

    expect($payment->Status)->toBe('successful')
        ->and($payment->TransactionID)->not->toBeNull()
        ->and($payment->PaymentDate)->not->toBeNull();
});

test('payment can be marked as failed', function () {
    $payment = Payment::factory()->failed()->create();

    expect($payment->Status)->toBe('failed')
        ->and($payment->PaymentResponse)->not->toBeNull();
});

test('payment amount is cast to decimal', function () {
    $payment = Payment::factory()->create(['Amount' => 125.75]);

    expect($payment->Amount)->toBe('125.75');
});

test('payment dates are cast correctly', function () {
    $payment = Payment::factory()->successful()->create();

    expect($payment->PaymentDate)->toBeInstanceOf(\Carbon\Carbon::class)
        ->and($payment->CreatedAt)->toBeInstanceOf(\Carbon\Carbon::class);
});

test('payment with pending status has no transaction id', function () {
    $payment = Payment::factory()->create(['Status' => 'pending']);

    expect($payment->TransactionID)->toBeNull()
        ->and($payment->PaymentDate)->toBeNull();
});
