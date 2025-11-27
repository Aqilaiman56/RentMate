<?php

use App\Models\Deposit;
use App\Models\Booking;

test('deposit can be created with valid data', function () {
    $booking = Booking::factory()->create();

    $deposit = Deposit::factory()->create([
        'BookingID' => $booking->BookingID,
        'DepositAmount' => 200.00,
        'Status' => 'held',
    ]);

    expect($deposit)->toBeInstanceOf(Deposit::class)
        ->and($deposit->BookingID)->toBe($booking->BookingID)
        ->and($deposit->DepositAmount)->toBe('200.00')
        ->and($deposit->Status)->toBe('held');
});

test('deposit belongs to booking', function () {
    $booking = Booking::factory()->create();
    $deposit = Deposit::factory()->create(['BookingID' => $booking->BookingID]);

    expect($deposit->booking)->toBeInstanceOf(Booking::class)
        ->and($deposit->booking->BookingID)->toBe($booking->BookingID);
});

test('deposit held scope filters held deposits', function () {
    Deposit::factory()->create(['Status' => 'held']);
    Deposit::factory()->create(['Status' => 'refunded']);
    Deposit::factory()->create(['Status' => 'held']);

    $heldDeposits = Deposit::held()->get();

    expect($heldDeposits)->toHaveCount(2);
});

test('deposit refunded scope filters refunded deposits', function () {
    Deposit::factory()->refunded()->create();
    Deposit::factory()->create(['Status' => 'held']);
    Deposit::factory()->refunded()->create();

    $refundedDeposits = Deposit::refunded()->get();

    expect($refundedDeposits)->toHaveCount(2);
});

test('deposit forfeited scope filters forfeited deposits', function () {
    Deposit::factory()->forfeited()->create();
    Deposit::factory()->create(['Status' => 'held']);
    Deposit::factory()->forfeited()->create();

    $forfeitedDeposits = Deposit::forfeited()->get();

    expect($forfeitedDeposits)->toHaveCount(2);
});

test('deposit can be refunded when status is held', function () {
    $deposit = Deposit::factory()->create(['Status' => 'held']);

    expect($deposit->canRefund())->toBeTrue();
});

test('deposit can be refunded when status is partial', function () {
    $deposit = Deposit::factory()->partial()->create();

    expect($deposit->canRefund())->toBeTrue();
});

test('deposit cannot be refunded when status is refunded', function () {
    $deposit = Deposit::factory()->refunded()->create();

    expect($deposit->canRefund())->toBeFalse();
});

test('deposit cannot be refunded when status is forfeited', function () {
    $deposit = Deposit::factory()->forfeited()->create();

    expect($deposit->canRefund())->toBeFalse();
});

test('deposit amount is cast to decimal', function () {
    $deposit = Deposit::factory()->create(['DepositAmount' => 150.50]);

    expect($deposit->DepositAmount)->toBe('150.50');
});

test('deposit dates are cast correctly', function () {
    $deposit = Deposit::factory()->create([
        'DateCollected' => '2024-01-15',
        'RefundDate' => '2024-01-20',
    ]);

    expect($deposit->DateCollected)->toBeInstanceOf(\Carbon\Carbon::class)
        ->and($deposit->RefundDate)->toBeInstanceOf(\Carbon\Carbon::class);
});
