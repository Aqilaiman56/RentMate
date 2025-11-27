<?php

use App\Models\Booking;
use App\Models\User;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Deposit;
use App\Models\Penalty;
use App\Models\ServiceFee;

test('booking can be created with valid data', function () {
    $user = User::factory()->create();
    $item = Item::factory()->create();

    $booking = Booking::factory()->create([
        'UserID' => $user->UserID,
        'ItemID' => $item->ItemID,
        'StartDate' => now()->addDays(5),
        'EndDate' => now()->addDays(10),
        'Status' => 'Pending',
    ]);

    expect($booking)->toBeInstanceOf(Booking::class)
        ->and($booking->UserID)->toBe($user->UserID)
        ->and($booking->ItemID)->toBe($item->ItemID)
        ->and($booking->Status)->toBe('Pending')
        ->and($booking->ReturnConfirmed)->toBeFalse();
});

test('booking belongs to user', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()->create(['UserID' => $user->UserID]);

    expect($booking->user)->toBeInstanceOf(User::class)
        ->and($booking->user->UserID)->toBe($user->UserID);
});

test('booking belongs to item', function () {
    $item = Item::factory()->create();
    $booking = Booking::factory()->create(['ItemID' => $item->ItemID]);

    expect($booking->item)->toBeInstanceOf(Item::class)
        ->and($booking->item->ItemID)->toBe($item->ItemID);
});

test('booking has payment relationship', function () {
    $booking = Booking::factory()->create();
    $payment = Payment::factory()->create(['BookingID' => $booking->BookingID]);

    expect($booking->payment)->toBeInstanceOf(Payment::class)
        ->and($booking->payment->PaymentID)->toBe($payment->PaymentID);
});

test('booking has deposit relationship', function () {
    $booking = Booking::factory()->create();
    $deposit = Deposit::factory()->create(['BookingID' => $booking->BookingID]);

    expect($booking->deposit)->toBeInstanceOf(Deposit::class)
        ->and($booking->deposit->DepositID)->toBe($deposit->DepositID);
});

test('booking has penalties relationship', function () {
    $booking = Booking::factory()->create();
    $penalty = Penalty::factory()->create(['BookingID' => $booking->BookingID]);

    expect($booking->penalties)->toHaveCount(1)
        ->and($booking->penalties->first()->PenaltyID)->toBe($penalty->PenaltyID);
});

test('booking is active when status is approved', function () {
    $booking = Booking::factory()->approved()->create();

    expect($booking->isActive())->toBeTrue();
});

test('booking is not active when status is not approved', function () {
    $booking = Booking::factory()->create(['Status' => 'Pending']);

    expect($booking->isActive())->toBeFalse();
});

test('booking approved scope filters approved bookings', function () {
    Booking::factory()->approved()->create();
    Booking::factory()->create(['Status' => 'Pending']);
    Booking::factory()->approved()->create();

    $approvedBookings = Booking::approved()->get();

    expect($approvedBookings)->toHaveCount(2);
});

test('booking between dates scope filters overlapping bookings', function () {
    // Create bookings with different date ranges
    $booking1 = Booking::factory()->create([
        'StartDate' => now()->addDays(5),
        'EndDate' => now()->addDays(10),
    ]);

    $booking2 = Booking::factory()->create([
        'StartDate' => now()->addDays(15),
        'EndDate' => now()->addDays(20),
    ]);

    $booking3 = Booking::factory()->create([
        'StartDate' => now()->addDays(7),
        'EndDate' => now()->addDays(12),
    ]);

    // Query for bookings between days 6 and 11
    $overlappingBookings = Booking::betweenDates(
        now()->addDays(6),
        now()->addDays(11)
    )->get();

    // Should find booking1 and booking3 (but not booking2)
    expect($overlappingBookings)->toHaveCount(2);
});

test('booking dates are cast correctly', function () {
    $booking = Booking::factory()->create([
        'StartDate' => '2024-01-15',
        'EndDate' => '2024-01-20',
    ]);

    expect($booking->StartDate)->toBeInstanceOf(\Carbon\Carbon::class)
        ->and($booking->EndDate)->toBeInstanceOf(\Carbon\Carbon::class);
});

test('booking service fee amount is cast to decimal', function () {
    $booking = Booking::factory()->create([
        'ServiceFeeAmount' => 25.50,
    ]);

    expect($booking->ServiceFeeAmount)->toBe('25.50');
});

test('booking total paid is cast to decimal', function () {
    $booking = Booking::factory()->create([
        'TotalPaid' => 150.75,
    ]);

    expect($booking->TotalPaid)->toBe('150.75');
});
