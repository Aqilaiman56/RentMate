<?php

use App\Models\User;
use App\Models\Item;
use App\Models\Booking;
use App\Models\Report;
use App\Models\Penalty;
use App\Models\Category;
use App\Models\Location;

use function Pest\Laravel\{actingAs, post, get, assertDatabaseHas};

describe('Admin Operations Integration', function () {

    test('admin can suspend user account with reason and duration', function () {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create([
            'IsSuspended' => false,
        ]);

        $suspendUntil = now()->addDays(30);

        // Admin suspends user
        $response = actingAs($admin)->post("/admin/users/{$user->UserID}/suspend", [
            'suspension_reason' => 'Multiple policy violations',
            'suspended_until' => $suspendUntil->format('Y-m-d'),
        ]);

        $user->refresh();
        expect($user->IsSuspended)->toBeTrue();
        expect($user->SuspensionReason)->toBe('Multiple policy violations');
        expect($user->SuspendedByAdminID)->toBe($admin->UserID);
        expect($user->SuspendedUntil)->not->toBeNull();

        // Verify notification sent to suspended user
        assertDatabaseHas('notifications', [
            'UserID' => $user->UserID,
            'Type' => 'admin',
        ]);
    });

    test('admin can unsuspend previously suspended user', function () {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->suspended()->create([
            'SuspensionReason' => 'Previous violation',
        ]);

        // Admin unsuspends user
        $response = actingAs($admin)->post("/admin/users/{$user->UserID}/unsuspend");

        $user->refresh();
        expect($user->IsSuspended)->toBeFalse();
        expect($user->SuspendedUntil)->toBeNull();
    });

    test('admin can review and approve report with penalty creation', function () {
        $admin = User::factory()->admin()->create();
        $reporter = User::factory()->create();
        $reportedUser = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $reporter->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $reportedUser->UserID,
            'ItemID' => $item->ItemID,
            'Status' => 'Completed',
        ]);

        $report = Report::factory()->create([
            'ReportedByID' => $reporter->UserID,
            'ReportedUserID' => $reportedUser->UserID,
            'BookingID' => $booking->BookingID,
            'ItemID' => $item->ItemID,
            'Status' => 'pending',
            'ReportType' => 'Damage',
            'Description' => 'Item returned with scratches',
        ]);

        // Admin reviews and approves report
        $response = actingAs($admin)->post("/admin/reports/{$report->ReportID}/resolve", [
            'action' => 'approve',
            'penalty_amount' => 150.00,
            'admin_notes' => 'Evidence verified, damage confirmed',
        ]);

        $report->refresh();
        expect($report->Status)->toBe('resolved');
        expect($report->ReviewedByAdminID)->toBe($admin->UserID);
        expect($report->AdminNotes)->not->toBeNull();

        // Verify penalty created
        assertDatabaseHas('penalty', [
            'ReportID' => $report->ReportID,
            'ReportedUserID' => $reportedUser->UserID,
            'PenaltyAmount' => '150.00',
            'ApprovedByAdminID' => $admin->UserID,
            'ResolvedStatus' => false,
        ]);

        // Verify notification sent to both parties
        assertDatabaseHas('notifications', [
            'UserID' => $reportedUser->UserID,
        ]);
    });

    test('admin can dismiss report without penalty', function () {
        $admin = User::factory()->admin()->create();
        $reporter = User::factory()->create();
        $reportedUser = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $reporter->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $report = Report::factory()->create([
            'ReportedByID' => $reporter->UserID,
            'ReportedUserID' => $reportedUser->UserID,
            'ItemID' => $item->ItemID,
            'Status' => 'pending',
        ]);

        // Admin dismisses report
        $response = actingAs($admin)->post("/admin/reports/{$report->ReportID}/dismiss", [
            'admin_notes' => 'Insufficient evidence provided',
        ]);

        $report->refresh();
        expect($report->Status)->toBe('dismissed');
        expect($report->AdminNotes)->toContain('Insufficient evidence');

        // Verify no penalty created
        assertDatabaseMissing('penalty', [
            'ReportID' => $report->ReportID,
        ]);
    });

    test('admin can resolve penalty after payment', function () {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => User::factory()->create()->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $user->UserID,
            'ItemID' => $item->ItemID,
        ]);

        $report = Report::factory()->create([
            'ReportedUserID' => $user->UserID,
            'BookingID' => $booking->BookingID,
        ]);

        $penalty = Penalty::factory()->create([
            'ReportID' => $report->ReportID,
            'ReportedUserID' => $user->UserID,
            'PenaltyAmount' => 100.00,
            'ResolvedStatus' => false,
        ]);

        // Admin marks penalty as resolved
        $response = actingAs($admin)->post("/admin/penalties/{$penalty->PenaltyID}/resolve");

        $penalty->refresh();
        expect($penalty->ResolvedStatus)->toBeTrue();
    });

    test('admin dashboard displays accurate statistics', function () {
        $admin = User::factory()->admin()->create();

        // Create test data
        User::factory()->count(10)->create(); // Regular users
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $owner = User::factory()->create();
        Item::factory()->count(5)->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        Report::factory()->count(3)->create(['Status' => 'pending']);
        Report::factory()->count(2)->create(['Status' => 'resolved']);

        // Admin views dashboard
        $response = actingAs($admin)->get('/admin');

        $response->assertOk();
        $response->assertSee('10'); // Total users (excluding admins)
        $response->assertSee('5');  // Total items
        $response->assertSee('3');  // Pending reports
    });

    test('admin can export users to CSV', function () {
        $admin = User::factory()->admin()->create();

        User::factory()->count(5)->create();

        // Admin exports users
        $response = actingAs($admin)->get('/admin/users/export');

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $response->assertHeader('content-disposition', 'attachment; filename="users.csv"');
    });

    test('admin can export reports to CSV', function () {
        $admin = User::factory()->admin()->create();

        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => User::factory()->create()->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        Report::factory()->count(3)->create([
            'ItemID' => $item->ItemID,
        ]);

        // Admin exports reports
        $response = actingAs($admin)->get('/admin/reports/export');

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    });

    test('admin can view user activity and history', function () {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => User::factory()->create()->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Create user activity
        Booking::factory()->count(3)->create([
            'UserID' => $user->UserID,
            'ItemID' => $item->ItemID,
        ]);

        // Admin views user details
        $response = actingAs($admin)->get("/admin/users/{$user->UserID}");

        $response->assertOk();
        $response->assertSee($user->UserName);
        $response->assertSee($user->Email);
    });

    test('admin can delete listing without active bookings', function () {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Admin deletes item
        $response = actingAs($admin)->delete("/admin/listings/{$item->ItemID}");

        assertDatabaseMissing('items', [
            'ItemID' => $item->ItemID,
        ]);
    });

    test('admin cannot delete listing with active bookings', function () {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Create active booking
        Booking::factory()->create([
            'ItemID' => $item->ItemID,
            'Status' => 'Approved',
        ]);

        // Admin attempts to delete item
        $response = actingAs($admin)->delete("/admin/listings/{$item->ItemID}");

        $response->assertSessionHasErrors();

        // Verify item still exists
        assertDatabaseHas('items', [
            'ItemID' => $item->ItemID,
        ]);
    });

    test('admin can search and filter users', function () {
        $admin = User::factory()->admin()->create();

        $user1 = User::factory()->create(['UserName' => 'John Doe', 'Email' => 'john@test.com']);
        $user2 = User::factory()->create(['UserName' => 'Jane Smith', 'Email' => 'jane@test.com']);
        $suspendedUser = User::factory()->suspended()->create(['UserName' => 'Bad User']);

        // Admin searches by name
        $response = actingAs($admin)->get('/admin/users?search=John');
        $response->assertOk();
        $response->assertSee('John Doe');
        $response->assertDontSee('Jane Smith');

        // Admin filters by suspended status
        $response = actingAs($admin)->get('/admin/users?status=suspended');
        $response->assertOk();
        $response->assertSee('Bad User');
    });

    test('non-admin cannot access admin routes', function () {
        $regularUser = User::factory()->create(['IsAdmin' => false]);

        // Attempt to access admin dashboard
        $response = actingAs($regularUser)->get('/admin');

        $response->assertForbidden();
    });

    test('admin actions create audit trail via notifications', function () {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        // Admin suspends user
        actingAs($admin)->post("/admin/users/{$user->UserID}/suspend", [
            'suspension_reason' => 'Test violation',
            'suspended_until' => now()->addDays(7)->format('Y-m-d'),
        ]);

        // Verify notification created for audit
        assertDatabaseHas('notifications', [
            'UserID' => $user->UserID,
            'Type' => 'admin',
        ]);
    });
});
