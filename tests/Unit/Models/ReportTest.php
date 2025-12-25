<?php

use App\Models\Report;
use App\Models\User;
use App\Models\Booking;
use App\Models\Item;
use App\Models\Penalty;

test('report can be created with valid data', function () {
    $reporter = User::factory()->create();
    $reportedUser = User::factory()->create();

    $report = Report::factory()->create([
        'ReportedByID' => $reporter->UserID,
        'ReportedUserID' => $reportedUser->UserID,
        'ReportType' => 'item-damage',
        'Subject' => 'Item damaged',
        'Status' => 'pending',
    ]);

    expect($report)->toBeInstanceOf(Report::class)
        ->and($report->ReportedByID)->toBe($reporter->UserID)
        ->and($report->ReportedUserID)->toBe($reportedUser->UserID)
        ->and($report->ReportType)->toBe('item-damage')
        ->and($report->Status)->toBe('pending');
});

test('report belongs to reporter', function () {
    $reporter = User::factory()->create();
    $report = Report::factory()->create(['ReportedByID' => $reporter->UserID]);

    expect($report->reporter)->toBeInstanceOf(User::class)
        ->and($report->reporter->UserID)->toBe($reporter->UserID);
});

test('report belongs to reported user', function () {
    $reportedUser = User::factory()->create();
    $report = Report::factory()->create(['ReportedUserID' => $reportedUser->UserID]);

    expect($report->reportedUser)->toBeInstanceOf(User::class)
        ->and($report->reportedUser->UserID)->toBe($reportedUser->UserID);
});

test('report belongs to booking', function () {
    $booking = Booking::factory()->create();
    $report = Report::factory()->create(['BookingID' => $booking->BookingID]);

    expect($report->booking)->toBeInstanceOf(Booking::class)
        ->and($report->booking->BookingID)->toBe($booking->BookingID);
});

test('report belongs to item', function () {
    $item = Item::factory()->create();
    $report = Report::factory()->create(['ItemID' => $item->ItemID]);

    expect($report->item)->toBeInstanceOf(Item::class)
        ->and($report->item->ItemID)->toBe($item->ItemID);
});

test('report belongs to reviewer admin', function () {
    $admin = User::factory()->create();
    $report = Report::factory()->resolved()->create(['ReviewedByAdminID' => $admin->UserID]);

    expect($report->reviewer)->toBeInstanceOf(User::class)
        ->and($report->reviewer->UserID)->toBe($admin->UserID);
});

test('report has penalty relationship', function () {
    $report = Report::factory()->create();
    $penalty = Penalty::factory()->create(['ReportID' => $report->ReportID]);

    expect($report->penalty)->toBeInstanceOf(Penalty::class)
        ->and($report->penalty->PenaltyID)->toBe($penalty->PenaltyID);
});

test('report pending scope filters pending reports', function () {
    Report::factory()->create(['Status' => 'pending']);
    Report::factory()->resolved()->create();
    Report::factory()->create(['Status' => 'pending']);

    $pendingReports = Report::pending()->get();

    expect($pendingReports)->toHaveCount(2);
});

test('report resolved scope filters resolved reports', function () {
    Report::factory()->resolved()->create();
    Report::factory()->create(['Status' => 'pending']);
    Report::factory()->resolved()->create();

    $resolvedReports = Report::resolved()->get();

    expect($resolvedReports)->toHaveCount(2);
});

test('report has penalty returns true when penalty exists', function () {
    $report = Report::factory()->create();
    Penalty::factory()->create(['ReportID' => $report->ReportID]);

    expect($report->hasPenalty())->toBeTrue();
});

test('report has penalty returns false when no penalty exists', function () {
    $report = Report::factory()->create();

    expect($report->hasPenalty())->toBeFalse();
});

test('report can be dismissed', function () {
    $report = Report::factory()->dismissed()->create();

    expect($report->Status)->toBe('dismissed')
        ->and($report->AdminNotes)->not->toBeNull()
        ->and($report->DateResolved)->not->toBeNull();
});

test('report dates are cast correctly', function () {
    $report = Report::factory()->create([
        'DateReported' => '2024-01-15',
    ]);

    expect($report->DateReported)->toBeInstanceOf(\Carbon\Carbon::class);
});
