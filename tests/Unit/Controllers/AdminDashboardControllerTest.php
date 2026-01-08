<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Admin\DashboardController;
use App\Models\User;
use App\Models\Item;
use App\Models\Booking;
use App\Models\Deposit;
use App\Models\Penalty;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = new DashboardController();

        // Create admin user
        $this->admin = User::factory()->create([
            'IsAdmin' => true,
            'UserName' => 'admin',
            'Email' => 'admin@rentmate.com',
            'PasswordHash' => Hash::make('password123')
        ]);

        $this->actingAs($this->admin);
    }

    /**
     * UT-ADMIN-001: Test dashboard calculates total non-admin users correctly
     */
    public function test_UT_ADMIN_001_dashboard_calculates_total_users_correctly()
    {
        // Create test users (non-admin)
        User::factory()->count(5)->create(['IsAdmin' => false]);
        User::factory()->count(2)->create(['IsAdmin' => true]); // These should not be counted

        $totalUsers = User::where('IsAdmin', 0)->count();

        $this->assertEquals(5, $totalUsers);
    }

    /**
     * UT-ADMIN-002: Test total listings count calculation
     */
    public function test_UT_ADMIN_002_total_listings_count_calculated_correctly()
    {
        // Create test items
        Item::factory()->count(10)->create();

        $totalListings = Item::count();

        $this->assertEquals(10, $totalListings);
    }

    /**
     * UT-ADMIN-003: Test total deposits calculation (held + refunded only)
     */
    public function test_UT_ADMIN_003_total_deposits_calculated_correctly()
    {
        $booking1 = Booking::factory()->create();
        $booking2 = Booking::factory()->create();
        $booking3 = Booking::factory()->create();

        // Create deposits with different statuses
        Deposit::factory()->create([
            'BookingID' => $booking1->BookingID,
            'DepositAmount' => 100.00,
            'Status' => 'held'
        ]);

        Deposit::factory()->create([
            'BookingID' => $booking2->BookingID,
            'DepositAmount' => 200.00,
            'Status' => 'refunded'
        ]);

        // This should not be counted (forfeited status)
        Deposit::factory()->create([
            'BookingID' => $booking3->BookingID,
            'DepositAmount' => 50.00,
            'Status' => 'forfeited'
        ]);

        $totalDeposits = Deposit::whereIn('Status', ['held', 'refunded'])
            ->sum('DepositAmount');

        $this->assertEquals(300.00, (float)$totalDeposits);
    }

    /**
     * UT-ADMIN-004: Test pending reports count
     */
    public function test_UT_ADMIN_004_pending_reports_count_calculated()
    {
        // Create resolved penalties
        Penalty::factory()->count(3)->create(['ResolvedStatus' => true]);

        // Create pending penalties
        Penalty::factory()->count(5)->create(['ResolvedStatus' => false]);

        $totalReports = Penalty::count();
        $pendingReports = Penalty::where('ResolvedStatus', 0)->count();

        $this->assertEquals(8, $totalReports);
        $this->assertEquals(5, $pendingReports);
    }

    /**
     * UT-ADMIN-005: Test total penalties count and amount calculation
     */
    public function test_UT_ADMIN_005_penalties_count_and_amount_calculated()
    {
        // Create penalties with amounts
        Penalty::factory()->create(['PenaltyAmount' => 100.00]);
        Penalty::factory()->create(['PenaltyAmount' => 150.00]);
        Penalty::factory()->create(['PenaltyAmount' => 50.00]);

        // This should not be counted (zero amount)
        Penalty::factory()->create(['PenaltyAmount' => 0.00]);

        $totalPenalties = Penalty::whereNotNull('PenaltyAmount')
            ->where('PenaltyAmount', '>', 0)
            ->count();

        $totalPenaltyAmount = Penalty::sum('PenaltyAmount');

        $this->assertEquals(3, $totalPenalties);
        $this->assertEquals(300.00, (float)$totalPenaltyAmount);
    }

    /**
     * UT-ADMIN-006: Test service fee calculation from bookings
     */
    public function test_UT_ADMIN_006_service_fees_calculated_correctly()
    {
        // Create bookings with different statuses
        Booking::factory()->create([
            'Status' => 'completed',
            'ServiceFeeAmount' => 1.00
        ]);

        Booking::factory()->create([
            'Status' => 'approved',
            'ServiceFeeAmount' => 1.00
        ]);

        // These should not be counted
        Booking::factory()->create([
            'Status' => 'pending',
            'ServiceFeeAmount' => 1.00
        ]);

        Booking::factory()->create([
            'Status' => 'cancelled',
            'ServiceFeeAmount' => 1.00
        ]);

        $serviceFeeCount = Booking::whereIn('Status', ['completed', 'approved'])->count();
        $totalServiceFeeAmount = Booking::whereIn('Status', ['completed', 'approved'])
            ->sum('ServiceFeeAmount');

        $this->assertEquals(2, $serviceFeeCount);
        $this->assertEquals(2.00, (float)$totalServiceFeeAmount);
    }

    /**
     * UT-ADMIN-007: Test notifications retrieval method
     */
    public function test_UT_ADMIN_007_notifications_retrieved_correctly()
    {
        // Create notifications for admin
        Notification::factory()->count(3)->create([
            'UserID' => $this->admin->UserID,
            'IsRead' => false
        ]);

        Notification::factory()->count(2)->create([
            'UserID' => $this->admin->UserID,
            'IsRead' => true
        ]);

        $notifications = $this->controller->getNotifications();

        $this->assertEquals(3, $notifications['total_count']);
        $this->assertIsArray($notifications['items']);
        $this->assertIsArray($notifications['counts']);
    }

    /**
     * UT-ADMIN-008: Test admin activity statistics calculation
     */
    public function test_UT_ADMIN_008_admin_activity_stats_calculated()
    {
        // Create penalties resolved by this admin
        Penalty::factory()->count(5)->create([
            'ApprovedByAdminID' => $this->admin->UserID,
            'ResolvedStatus' => true
        ]);

        // Create penalties with amounts
        Penalty::factory()->count(3)->create([
            'ApprovedByAdminID' => $this->admin->UserID,
            'PenaltyAmount' => 100.00
        ]);

        $resolvedReports = Penalty::where('ApprovedByAdminID', $this->admin->UserID)
            ->where('ResolvedStatus', 1)
            ->count();

        $totalPenaltiesIssued = Penalty::where('ApprovedByAdminID', $this->admin->UserID)
            ->where('PenaltyAmount', '>', 0)
            ->count();

        $this->assertEquals(5, $resolvedReports);
        $this->assertEquals(3, $totalPenaltiesIssued);
    }

    /**
     * UT-ADMIN-009: Test recent activity query returns correct limit
     */
    public function test_UT_ADMIN_009_recent_activity_limited_to_10_items()
    {
        // Create more than 10 penalties
        Penalty::factory()->count(15)->create([
            'ApprovedByAdminID' => $this->admin->UserID,
            'DateReported' => now()->subDays(rand(1, 30))
        ]);

        $recentActivity = Penalty::where('ApprovedByAdminID', $this->admin->UserID)
            ->with(['reportedUser', 'item'])
            ->orderBy('DateReported', 'desc')
            ->limit(10)
            ->get();

        $this->assertCount(10, $recentActivity);
    }

    /**
     * UT-ADMIN-010: Test recent activity ordered by date descending
     */
    public function test_UT_ADMIN_010_recent_activity_ordered_by_date_descending()
    {
        $oldPenalty = Penalty::factory()->create([
            'ApprovedByAdminID' => $this->admin->UserID,
            'DateReported' => now()->subDays(10)
        ]);

        $newPenalty = Penalty::factory()->create([
            'ApprovedByAdminID' => $this->admin->UserID,
            'DateReported' => now()->subDays(1)
        ]);

        $recentActivity = Penalty::where('ApprovedByAdminID', $this->admin->UserID)
            ->orderBy('DateReported', 'desc')
            ->get();

        $this->assertEquals($newPenalty->PenaltyID, $recentActivity->first()->PenaltyID);
    }

    /**
     * UT-ADMIN-011: Test report approval updates penalty correctly
     */
    public function test_UT_ADMIN_011_report_approval_updates_penalty()
    {
        $penalty = Penalty::factory()->create([
            'ResolvedStatus' => false,
            'PenaltyAmount' => 0
        ]);

        $penalty->update([
            'ApprovedByAdminID' => $this->admin->UserID,
            'ResolvedStatus' => true,
            'PenaltyAmount' => 150.00
        ]);

        $this->assertDatabaseHas('penalties', [
            'PenaltyID' => $penalty->PenaltyID,
            'ApprovedByAdminID' => $this->admin->UserID,
            'ResolvedStatus' => true,
            'PenaltyAmount' => 150.00
        ]);
    }

    /**
     * UT-ADMIN-012: Test report rejection sets penalty amount to zero
     */
    public function test_UT_ADMIN_012_report_rejection_sets_penalty_to_zero()
    {
        $penalty = Penalty::factory()->create([
            'ResolvedStatus' => false,
            'PenaltyAmount' => 100.00
        ]);

        $penalty->update([
            'ApprovedByAdminID' => $this->admin->UserID,
            'ResolvedStatus' => true,
            'PenaltyAmount' => 0
        ]);

        $this->assertDatabaseHas('penalties', [
            'PenaltyID' => $penalty->PenaltyID,
            'ApprovedByAdminID' => $this->admin->UserID,
            'ResolvedStatus' => true,
            'PenaltyAmount' => 0
        ]);
    }

    /**
     * UT-ADMIN-013: Test listing can be deleted when no active bookings
     */
    public function test_UT_ADMIN_013_listing_deletable_without_active_bookings()
    {
        $item = Item::factory()->create();

        // Create completed booking (not active)
        Booking::factory()->create([
            'ItemID' => $item->ItemID,
            'Status' => 'completed',
            'EndDate' => now()->subDays(5)
        ]);

        $hasActiveBookings = Booking::where('ItemID', $item->ItemID)
            ->where('Status', 'approved')
            ->where('EndDate', '>=', now())
            ->exists();

        $this->assertFalse($hasActiveBookings);

        // Can delete
        $item->delete();
        $this->assertDatabaseMissing('items', ['ItemID' => $item->ItemID]);
    }

    /**
     * UT-ADMIN-014: Test listing cannot be deleted with active bookings
     */
    public function test_UT_ADMIN_014_listing_not_deletable_with_active_bookings()
    {
        $item = Item::factory()->create();

        // Create active booking
        Booking::factory()->create([
            'ItemID' => $item->ItemID,
            'Status' => 'approved',
            'EndDate' => now()->addDays(5)
        ]);

        $hasActiveBookings = Booking::where('ItemID', $item->ItemID)
            ->where('Status', 'approved')
            ->where('EndDate', '>=', now())
            ->exists();

        $this->assertTrue($hasActiveBookings);
    }

    /**
     * UT-ADMIN-015: Test password change with correct current password
     */
    public function test_UT_ADMIN_015_password_changed_with_correct_current_password()
    {
        $currentPassword = 'password123';
        $newPassword = 'newpassword456';

        // Verify current password
        $this->assertTrue(Hash::check($currentPassword, $this->admin->PasswordHash));

        // Update password
        $this->admin->update([
            'PasswordHash' => Hash::make($newPassword)
        ]);

        // Verify new password works
        $this->admin->refresh();
        $this->assertTrue(Hash::check($newPassword, $this->admin->PasswordHash));
    }

    /**
     * UT-ADMIN-016: Test password verification fails with incorrect password
     */
    public function test_UT_ADMIN_016_password_verification_fails_with_wrong_password()
    {
        $correctPassword = 'password123';
        $wrongPassword = 'wrongpassword';

        $this->assertTrue(Hash::check($correctPassword, $this->admin->PasswordHash));
        $this->assertFalse(Hash::check($wrongPassword, $this->admin->PasswordHash));
    }

    /**
     * UT-ADMIN-017: Test system statistics aggregation
     */
    public function test_UT_ADMIN_017_system_statistics_aggregated_correctly()
    {
        User::factory()->count(10)->create(['IsAdmin' => false]);
        User::factory()->count(2)->create(['IsAdmin' => true]);
        Item::factory()->count(15)->create();
        Penalty::factory()->count(5)->create(['ResolvedStatus' => false]);

        $totalUsers = User::where('IsAdmin', 0)->count();
        $totalAdmins = User::where('IsAdmin', 1)->count();
        $totalListings = Item::count();
        $pendingReports = Penalty::where('ResolvedStatus', 0)->count();

        $this->assertEquals(10, $totalUsers);
        $this->assertEquals(3, $totalAdmins); // Including the admin created in setUp
        $this->assertEquals(15, $totalListings);
        $this->assertEquals(5, $pendingReports);
    }

    /**
     * UT-ADMIN-018: Test dashboard handles zero data gracefully
     */
    public function test_UT_ADMIN_018_dashboard_handles_zero_data_gracefully()
    {
        // Don't create any data
        $totalUsers = User::where('IsAdmin', 0)->count();
        $totalListings = Item::count();
        $totalDeposits = Deposit::whereIn('Status', ['held', 'refunded'])
            ->sum('DepositAmount') ?? 0;
        $totalReports = Penalty::count();
        $pendingReports = Penalty::where('ResolvedStatus', 0)->count();

        $this->assertEquals(0, $totalUsers);
        $this->assertEquals(0, $totalListings);
        $this->assertEquals(0, (float)$totalDeposits);
        $this->assertEquals(0, $totalReports);
        $this->assertEquals(0, $pendingReports);
    }

    /**
     * UT-ADMIN-019: Test null deposits return zero
     */
    public function test_UT_ADMIN_019_null_deposits_return_zero()
    {
        // No deposits created
        $totalDeposits = Deposit::whereIn('Status', ['held', 'refunded'])
            ->sum('DepositAmount') ?? 0;

        $this->assertEquals(0, $totalDeposits);
        $this->assertIsNumeric($totalDeposits);
    }

    /**
     * UT-ADMIN-020: Test null penalty amounts return zero
     */
    public function test_UT_ADMIN_020_null_penalty_amounts_return_zero()
    {
        // No penalties created
        $totalPenaltyAmount = Penalty::sum('PenaltyAmount') ?? 0;

        $this->assertEquals(0, $totalPenaltyAmount);
        $this->assertIsNumeric($totalPenaltyAmount);
    }

    /**
     * UT-ADMIN-021: Test null service fees return zero
     */
    public function test_UT_ADMIN_021_null_service_fees_return_zero()
    {
        // No bookings created
        $totalServiceFeeAmount = Booking::whereIn('Status', ['completed', 'approved'])
            ->sum('ServiceFeeAmount') ?? 0;

        $this->assertEquals(0, $totalServiceFeeAmount);
        $this->assertIsNumeric($totalServiceFeeAmount);
    }

    /**
     * UT-ADMIN-022: Test only non-admin users can be suspended
     */
    public function test_UT_ADMIN_022_only_non_admin_users_can_be_suspended()
    {
        $regularUser = User::factory()->create(['IsAdmin' => false]);
        $adminUser = User::factory()->create(['IsAdmin' => true]);

        // Should find regular user
        $foundRegular = User::where('UserID', $regularUser->UserID)
            ->where('IsAdmin', 0)
            ->first();

        $this->assertNotNull($foundRegular);

        // Should not find admin user when filtering by IsAdmin=0
        $notFoundAdmin = User::where('UserID', $adminUser->UserID)
            ->where('IsAdmin', 0)
            ->first();

        $this->assertNull($notFoundAdmin);
    }

    /**
     * UT-ADMIN-023: Test profile image storage path generation
     */
    public function test_UT_ADMIN_023_profile_image_path_stored_correctly()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('profile.jpg');
        $path = $file->store('profile_images', 'public');

        $this->assertStringContainsString('profile_images/', $path);
        Storage::disk('public')->assertExists($path);
    }

    /**
     * UT-ADMIN-024: Test penalty amount decimal precision
     */
    public function test_UT_ADMIN_024_penalty_amount_decimal_precision()
    {
        $penalty = Penalty::factory()->create([
            'PenaltyAmount' => 123.45
        ]);

        $this->assertEquals('123.45', number_format($penalty->PenaltyAmount, 2, '.', ''));
    }

    /**
     * UT-ADMIN-025: Test service fee amount decimal precision
     */
    public function test_UT_ADMIN_025_service_fee_amount_decimal_precision()
    {
        $booking = Booking::factory()->create([
            'ServiceFeeAmount' => 1.00
        ]);

        $this->assertEquals('1.00', number_format($booking->ServiceFeeAmount, 2, '.', ''));
    }

    /**
     * UT-ADMIN-026: Test deposit amount decimal precision
     */
    public function test_UT_ADMIN_026_deposit_amount_decimal_precision()
    {
        $booking = Booking::factory()->create();
        $deposit = Deposit::factory()->create([
            'BookingID' => $booking->BookingID,
            'DepositAmount' => 250.75
        ]);

        $this->assertEquals('250.75', number_format($deposit->DepositAmount, 2, '.', ''));
    }

    /**
     * UT-ADMIN-027: Test multiple status filtering for bookings
     */
    public function test_UT_ADMIN_027_multiple_status_filtering_for_bookings()
    {
        Booking::factory()->create(['Status' => 'completed']);
        Booking::factory()->create(['Status' => 'approved']);
        Booking::factory()->create(['Status' => 'pending']);
        Booking::factory()->create(['Status' => 'cancelled']);

        $validStatuses = Booking::whereIn('Status', ['completed', 'approved'])->count();

        $this->assertEquals(2, $validStatuses);
    }

    /**
     * UT-ADMIN-028: Test multiple status filtering for deposits
     */
    public function test_UT_ADMIN_028_multiple_status_filtering_for_deposits()
    {
        $booking1 = Booking::factory()->create();
        $booking2 = Booking::factory()->create();
        $booking3 = Booking::factory()->create();
        $booking4 = Booking::factory()->create();

        Deposit::factory()->create(['BookingID' => $booking1->BookingID, 'Status' => 'held']);
        Deposit::factory()->create(['BookingID' => $booking2->BookingID, 'Status' => 'refunded']);
        Deposit::factory()->create(['BookingID' => $booking3->BookingID, 'Status' => 'forfeited']);
        Deposit::factory()->create(['BookingID' => $booking4->BookingID, 'Status' => 'partial']);

        $validDeposits = Deposit::whereIn('Status', ['held', 'refunded'])->count();

        $this->assertEquals(2, $validDeposits);
    }

    /**
     * UT-ADMIN-029: Test boolean status filtering (resolved/unresolved)
     */
    public function test_UT_ADMIN_029_boolean_status_filtering()
    {
        Penalty::factory()->count(3)->create(['ResolvedStatus' => true]);
        Penalty::factory()->count(5)->create(['ResolvedStatus' => false]);

        $resolved = Penalty::where('ResolvedStatus', 1)->count();
        $unresolved = Penalty::where('ResolvedStatus', 0)->count();

        $this->assertEquals(3, $resolved);
        $this->assertEquals(5, $unresolved);
    }

    /**
     * UT-ADMIN-030: Test unread notification filtering
     */
    public function test_UT_ADMIN_030_unread_notification_filtering()
    {
        Notification::factory()->count(4)->create([
            'UserID' => $this->admin->UserID,
            'IsRead' => false
        ]);

        Notification::factory()->count(3)->create([
            'UserID' => $this->admin->UserID,
            'IsRead' => true
        ]);

        $unreadCount = Notification::where('UserID', $this->admin->UserID)
            ->where('IsRead', false)
            ->count();

        $this->assertEquals(4, $unreadCount);
    }
}
