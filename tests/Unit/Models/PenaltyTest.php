<?php

use App\Models\Penalty;
use App\Models\User;
use App\Models\Report;
use App\Models\Booking;
use App\Models\Item;

test('penalty can be created with valid data', function () {
    $reporter = User::factory()->create();
    $reportedUser = User::factory()->create();

    $penalty = Penalty::factory()->create([
        'ReportedByID' => $reporter->UserID,
        'ReportedUserID' => $reportedUser->UserID,
        'PenaltyAmount' => 100.00,
        'ResolvedStatus' => false,
    ]);

    expect($penalty)->toBeInstanceOf(Penalty::class)
        ->and($penalty->ReportedByID)->toBe($reporter->UserID)
        ->and($penalty->ReportedUserID)->toBe($reportedUser->UserID)
        ->and($penalty->PenaltyAmount)->toBe('100.00')
        ->and($penalty->ResolvedStatus)->toBeFalse();
});

test('penalty belongs to report', function () {
    $report = Report::factory()->create();
    $penalty = Penalty::factory()->create(['ReportID' => $report->ReportID]);

    expect($penalty->report)->toBeInstanceOf(Report::class)
        ->and($penalty->report->ReportID)->toBe($report->ReportID);
});

test('penalty belongs to reporter', function () {
    $reporter = User::factory()->create();
    $penalty = Penalty::factory()->create(['ReportedByID' => $reporter->UserID]);

    expect($penalty->reportedBy)->toBeInstanceOf(User::class)
        ->and($penalty->reportedBy->UserID)->toBe($reporter->UserID);
});

test('penalty belongs to reported user', function () {
    $reportedUser = User::factory()->create();
    $penalty = Penalty::factory()->create(['ReportedUserID' => $reportedUser->UserID]);

    expect($penalty->reportedUser)->toBeInstanceOf(User::class)
        ->and($penalty->reportedUser->UserID)->toBe($reportedUser->UserID);
});

test('penalty belongs to item', function () {
    $item = Item::factory()->create();
    $penalty = Penalty::factory()->create(['ItemID' => $item->ItemID]);

    expect($penalty->item)->toBeInstanceOf(Item::class)
        ->and($penalty->item->ItemID)->toBe($item->ItemID);
});

test('penalty belongs to booking', function () {
    $booking = Booking::factory()->create();
    $penalty = Penalty::factory()->create(['BookingID' => $booking->BookingID]);

    expect($penalty->booking)->toBeInstanceOf(Booking::class)
        ->and($penalty->booking->BookingID)->toBe($booking->BookingID);
});

test('penalty belongs to approved by admin', function () {
    $admin = User::factory()->create(['IsAdmin' => true]);
    $penalty = Penalty::factory()->create(['ApprovedByAdminID' => $admin->UserID]);

    expect($penalty->approvedByAdmin)->toBeInstanceOf(User::class)
        ->and($penalty->approvedByAdmin->UserID)->toBe($admin->UserID)
        ->and($penalty->approvedByAdmin->IsAdmin)->toBeTrue();
});

test('penalty pending scope filters pending penalties', function () {
    Penalty::factory()->create(['ResolvedStatus' => false]);
    Penalty::factory()->resolved()->create();
    Penalty::factory()->create(['ResolvedStatus' => false]);

    $pendingPenalties = Penalty::pending()->get();

    expect($pendingPenalties)->toHaveCount(2);
});

test('penalty resolved scope filters resolved penalties', function () {
    Penalty::factory()->resolved()->create();
    Penalty::factory()->create(['ResolvedStatus' => false]);
    Penalty::factory()->resolved()->create();

    $resolvedPenalties = Penalty::resolved()->get();

    expect($resolvedPenalties)->toHaveCount(2);
});

test('penalty with penalty scope filters penalties with amounts', function () {
    Penalty::factory()->create(['PenaltyAmount' => 100.00]);
    Penalty::factory()->create(['PenaltyAmount' => 0]);
    Penalty::factory()->create(['PenaltyAmount' => null]);
    Penalty::factory()->create(['PenaltyAmount' => 50.00]);

    $penaltiesWithAmount = Penalty::withPenalty()->get();

    expect($penaltiesWithAmount)->toHaveCount(2);
});

test('penalty amount is cast to decimal', function () {
    $penalty = Penalty::factory()->create(['PenaltyAmount' => 125.50]);

    expect($penalty->PenaltyAmount)->toBe('125.50');
});

test('penalty date is cast to datetime', function () {
    $penalty = Penalty::factory()->create(['DateReported' => '2024-01-15']);

    expect($penalty->DateReported)->toBeInstanceOf(\Carbon\Carbon::class);
});
