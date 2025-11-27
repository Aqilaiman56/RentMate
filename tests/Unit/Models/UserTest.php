<?php

use App\Models\User;
use App\Models\Item;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Penalty;

test('user can be created with valid data', function () {
    $user = User::factory()->create([
        'UserName' => 'John Doe',
        'Email' => 'john@example.com',
    ]);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->UserName)->toBe('John Doe')
        ->and($user->Email)->toBe('john@example.com')
        ->and($user->IsAdmin)->toBeFalse()
        ->and($user->IsSuspended)->toBeFalse();
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

test('user is not suspended by default', function () {
    $user = User::factory()->create();

    expect($user->isCurrentlySuspended())->toBeFalse();
});

test('user with permanent suspension is currently suspended', function () {
    $user = User::factory()->create([
        'IsSuspended' => true,
        'SuspendedUntil' => null, // Permanent suspension
    ]);

    expect($user->isCurrentlySuspended())->toBeTrue();
});

test('user with future suspension expiry is currently suspended', function () {
    $user = User::factory()->create([
        'IsSuspended' => true,
        'SuspendedUntil' => now()->addDays(7),
    ]);

    expect($user->isCurrentlySuspended())->toBeTrue();
});

test('user with past suspension expiry is auto-unsuspended', function () {
    $user = User::factory()->create([
        'IsSuspended' => true,
        'SuspendedUntil' => now()->subDay(),
    ]);

    $isSuspended = $user->isCurrentlySuspended();

    expect($isSuspended)->toBeFalse();

    $user->refresh();
    expect($user->IsSuspended)->toBeFalse()
        ->and($user->SuspendedUntil)->toBeNull();
});

test('user can be an admin', function () {
    $admin = User::factory()->create(['IsAdmin' => true]);
    $regularUser = User::factory()->create(['IsAdmin' => false]);

    expect($admin->IsAdmin)->toBeTrue()
        ->and($regularUser->IsAdmin)->toBeFalse();
});

test('user has suspended by relationship', function () {
    $admin = User::factory()->create(['IsAdmin' => true]);
    $user = User::factory()->create([
        'IsSuspended' => true,
        'SuspendedByAdminID' => $admin->UserID,
    ]);

    expect($user->suspendedBy)->toBeInstanceOf(User::class)
        ->and($user->suspendedBy->UserID)->toBe($admin->UserID);
});

test('user password hash is hidden', function () {
    $user = User::factory()->create();
    $array = $user->toArray();

    expect($array)->not->toHaveKey('PasswordHash')
        ->and($array)->not->toHaveKey('Password');
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
