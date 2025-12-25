<?php

use App\Models\User;
use App\Models\Item;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Penalty;

test('user can be created with valid data', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->name)->toBe('John Doe')
        ->and($user->email)->toBe('john@example.com');
});

test('user has items relationship', function () {
    $user = User::factory()->create();
    $item = Item::factory()->create(['UserID' => $user->UserID]);

    expect($user->items)->toHaveCount(1)
        ->and($user->items->first()->ItemID)->toBe($item->ItemID);
});

test('user has bookings relationship', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()->create(['UserID' => $user->UserID]);

    expect($user->bookings)->toHaveCount(1)
        ->and($user->bookings->first()->BookingID)->toBe($booking->BookingID);
});

test('user has reviews relationship', function () {
    $user = User::factory()->create();
    $review = Review::factory()->create(['UserID' => $user->UserID]);

    expect($user->reviews)->toHaveCount(1)
        ->and($user->reviews->first()->ReviewID)->toBe($review->ReviewID);
});

test('user password is hidden', function () {
    $user = User::factory()->create();
    $array = $user->toArray();

    expect($array)->not->toHaveKey('password');
});

test('user has reports made relationship', function () {
    $reporter = User::factory()->create();
    $reported = User::factory()->create();

    $penalty = Penalty::factory()->create([
        'ReportedByID' => $reporter->UserID,
        'ReportedUserID' => $reported->UserID,
    ]);

    expect($reporter->reportsMade)->toHaveCount(1)
        ->and($reporter->reportsMade->first()->PenaltyID)->toBe($penalty->PenaltyID);
});

test('user has reports received relationship', function () {
    $reporter = User::factory()->create();
    $reported = User::factory()->create();

    $penalty = Penalty::factory()->create([
        'ReportedByID' => $reporter->UserID,
        'ReportedUserID' => $reported->UserID,
    ]);

    expect($reported->reportsReceived)->toHaveCount(1)
        ->and($reported->reportsReceived->first()->PenaltyID)->toBe($penalty->PenaltyID);
});

test('user can update bank details', function () {
    $user = User::factory()->create();

    $user->update([
        'BankName' => 'Test Bank',
        'BankAccountNumber' => '1234567890',
        'BankAccountHolderName' => 'John Doe',
    ]);

    expect($user->BankName)->toBe('Test Bank')
        ->and($user->BankAccountNumber)->toBe('1234567890')
        ->and($user->BankAccountHolderName)->toBe('John Doe');
});
