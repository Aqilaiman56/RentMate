<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Models\Penalty;
use App\Models\Booking;
use App\Models\Deposit;
use App\Models\RefundQueue;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        // Total Users (non-admin)
        $totalUsers = User::where('IsAdmin', 0)->count();

        // Total Listings (all items)
        $totalListings = Item::count();

        // Total Deposits (sum of all deposits that are held or refunded)
        $totalDeposits = Deposit::whereIn('Status', ['held', 'refunded'])
            ->sum('DepositAmount') ?? 0;

        // Reports from Users (total penalties/reports)
        $totalReports = Penalty::count();
        $pendingReports = Penalty::where('ResolvedStatus', 0)->count();

        // Penalty Actions (penalties with amount > 0)
        $totalPenalties = Penalty::whereNotNull('PenaltyAmount')
            ->where('PenaltyAmount', '>', 0)
            ->count();
        $totalPenaltyAmount = Penalty::sum('PenaltyAmount') ?? 0;

        // Service Fee Transactions (from completed bookings)
        $serviceFeeCount = Booking::whereIn('Status', ['completed', 'approved'])->count();
        $totalServiceFeeAmount = Booking::whereIn('Status', ['completed', 'approved'])
            ->sum('ServiceFeeAmount') ?? 0;

        // Notifications
        $notifications = $this->getNotifications();

        return view('admin.AdminDashboard', compact(
            'totalUsers',
            'totalListings',
            'totalDeposits',
            'totalReports',
            'pendingReports',
            'totalPenalties',
            'totalPenaltyAmount',
            'serviceFeeCount',
            'totalServiceFeeAmount',
            'notifications'
        ));
    }

    /**
     * Get admin notifications
     */
    public function getNotifications()
    {
        $notifications = [];

        // Pending Reports
        $pendingReportsCount = Penalty::where('ResolvedStatus', 0)->count();
        if ($pendingReportsCount > 0) {
            $recentReports = Penalty::where('ResolvedStatus', 0)
                ->with(['reportedUser', 'item'])
                ->orderBy('DateReported', 'desc')
                ->limit(3)
                ->get();

            foreach ($recentReports as $report) {
                $notifications[] = [
                    'type' => 'report',
                    'icon' => 'fa-flag',
                    'color' => 'red',
                    'title' => 'New Report',
                    'message' => 'Report against ' . ($report->reportedUser->UserName ?? 'Unknown User'),
                    'time' => $report->DateReported->diffForHumans(),
                    'link' => route('admin.reports.show', $report->PenaltyID),
                    'badge' => $pendingReportsCount . ' pending'
                ];
            }
        }

        // Pending Refunds
        $pendingRefundsCount = RefundQueue::where('Status', 'pending')->count();
        if ($pendingRefundsCount > 0) {
            $recentRefunds = RefundQueue::where('Status', 'pending')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            foreach ($recentRefunds as $refund) {
                $notifications[] = [
                    'type' => 'refund',
                    'icon' => 'fa-hand-holding-usd',
                    'color' => 'orange',
                    'title' => 'Refund Pending',
                    'message' => 'RM ' . number_format($refund->RefundAmount, 2) . ' refund for ' . $refund->user->UserName,
                    'time' => $refund->created_at->diffForHumans(),
                    'link' => route('admin.refund-queue'),
                    'badge' => $pendingRefundsCount . ' pending'
                ];
            }
        }

        // New Deposits (held - potential refunds)
        $heldDepositsCount = Deposit::where('Status', 'held')
            ->whereHas('booking', function($q) {
                $q->where('Status', 'completed')
                  ->where('EndDate', '<', now());
            })
            ->count();

        if ($heldDepositsCount > 0) {
            $recentHeldDeposits = Deposit::where('Status', 'held')
                ->whereHas('booking', function($q) {
                    $q->where('Status', 'completed')
                      ->where('EndDate', '<', now());
                })
                ->with('booking.user')
                ->orderBy('DateCollected', 'desc')
                ->limit(2)
                ->get();

            foreach ($recentHeldDeposits as $deposit) {
                $notifications[] = [
                    'type' => 'deposit',
                    'icon' => 'fa-coins',
                    'color' => 'blue',
                    'title' => 'Deposit Ready for Refund',
                    'message' => 'RM ' . number_format($deposit->DepositAmount, 2) . ' for ' . $deposit->booking->user->UserName,
                    'time' => $deposit->booking->EndDate->diffForHumans(),
                    'link' => route('admin.deposits'),
                    'badge' => $heldDepositsCount . ' ready'
                ];
            }
        }

        // Active Penalties (approved and has penalty amount)
        $activePenaltiesCount = Penalty::where('ResolvedStatus', 1)
            ->where('PenaltyAmount', '>', 0)
            ->count();

        if ($activePenaltiesCount > 0) {
            $recentPenalties = Penalty::where('ResolvedStatus', 1)
                ->where('PenaltyAmount', '>', 0)
                ->with('reportedUser')
                ->orderBy('DateReported', 'desc')
                ->limit(2)
                ->get();

            foreach ($recentPenalties as $penalty) {
                $notifications[] = [
                    'type' => 'penalty',
                    'icon' => 'fa-exclamation-triangle',
                    'color' => 'yellow',
                    'title' => 'Active Penalty',
                    'message' => 'RM ' . number_format($penalty->PenaltyAmount, 2) . ' penalty for ' . ($penalty->reportedUser->UserName ?? 'Unknown User'),
                    'time' => $penalty->DateReported->diffForHumans(),
                    'link' => route('admin.penalties'),
                    'badge' => $activePenaltiesCount . ' active'
                ];
            }
        }

        // Sort by time (most recent first)
        usort($notifications, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return [
            'items' => array_slice($notifications, 0, 10), // Limit to 10 most recent
            'total_count' => count($notifications),
            'counts' => [
                'reports' => $pendingReportsCount,
                'refunds' => $pendingRefundsCount,
                'deposits' => $heldDepositsCount,
                'penalties' => $activePenaltiesCount,
            ]
        ];
    }

    /**
     * Display admin profile
     */
    public function profile()
    {
        $admin = Auth::user();
        
        // Get admin activity stats
        $resolvedReports = Penalty::where('ApprovedByAdminID', $admin->UserID)
            ->where('ResolvedStatus', 1)
            ->count();
        
        $totalPenaltiesIssued = Penalty::where('ApprovedByAdminID', $admin->UserID)
            ->where('PenaltyAmount', '>', 0)
            ->count();
        
        $recentActivity = Penalty::where('ApprovedByAdminID', $admin->UserID)
            ->with(['reportedUser', 'item'])
            ->orderBy('DateReported', 'desc')
            ->limit(10)
            ->get();

        return view('admin.profile', compact(
            'admin',
            'resolvedReports',
            'totalPenaltiesIssued',
            'recentActivity'
        ));
    }

    /**
     * Update admin profile
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::user();
        
        $validated = $request->validate([
            'UserName' => 'required|string|max:255',
            'Email' => 'required|email|unique:users,Email,' . $admin->UserID . ',UserID',
            'PhoneNumber' => 'nullable|string|max:20',
            'ProfileImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle profile image upload
        if ($request->hasFile('ProfileImage')) {
            // Delete old image if exists
            if ($admin->ProfileImage) {
                Storage::disk('public')->delete($admin->ProfileImage);
            }
            
            $validated['ProfileImage'] = $request->file('ProfileImage')->store('profile_images', 'public');
        }

        $admin->update($validated);

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully');
    }

    /**
     * Display admin settings
     */
    public function settings()
    {
        $admin = Auth::user();
        
        // Get system settings
        $totalUsers = User::where('IsAdmin', 0)->count();
        $totalAdmins = User::where('IsAdmin', 1)->count();
        $totalListings = Item::count();
        $pendingReports = Penalty::where('ResolvedStatus', 0)->count();

        return view('admin.settings', compact(
            'admin',
            'totalUsers',
            'totalAdmins',
            'totalListings',
            'pendingReports'
        ));
    }

    /**
     * Update admin password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $admin = Auth::user();

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $admin->Password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update password
        $admin->update([
            'Password' => Hash::make($validated['new_password'])
        ]);

        return back()->with('success', 'Password updated successfully');
    }

    /**
     * Approve a report and apply penalty
     */
    public function approveReport(Request $request, $penaltyId)
    {
        $validated = $request->validate([
            'penalty_amount' => 'required|numeric|min:0'
        ]);

        $penalty = Penalty::findOrFail($penaltyId);
        
        $penalty->update([
            'ApprovedByAdminID' => auth()->id(),
            'ResolvedStatus' => 1,
            'PenaltyAmount' => $validated['penalty_amount']
        ]);

        return redirect()->back()->with('success', 'Report approved and penalty of RM ' . number_format($validated['penalty_amount'], 2) . ' applied');
    }

    /**
     * Reject a report
     */
    public function rejectReport($penaltyId)
    {
        $penalty = Penalty::findOrFail($penaltyId);
        
        $penalty->update([
            'ApprovedByAdminID' => auth()->id(),
            'ResolvedStatus' => 1,
            'PenaltyAmount' => 0
        ]);

        return redirect()->back()->with('success', 'Report rejected successfully');
    }

    /**
     * Ban/Suspend a user
     */
    public function suspendUser(Request $request, $userId)
    {
        $user = User::where('UserID', $userId)
            ->where('IsAdmin', 0)
            ->firstOrFail();

        // Add suspension logic here (you may need to add a 'suspended' field to users table)
        // Example: $user->update(['is_suspended' => true, 'suspended_until' => now()->addDays(30)]);
        
        return redirect()->back()->with('success', 'User suspended successfully');
    }

    /**
     * Delete a listing (admin override)
     */
    public function deleteListing($itemId)
    {
        $item = Item::findOrFail($itemId);
        
        // Check if item has active bookings
        $hasActiveBookings = $item->booking()
            ->where('Status', 'Approved')
            ->where('EndDate', '>=', now())
            ->exists();
        
        if ($hasActiveBookings) {
            return back()->with('error', 'Cannot delete item with active bookings');
        }
        
        // Delete image
        if ($item->ImagePath) {
            Storage::disk('public')->delete($item->ImagePath);
        }
        
        $item->delete();
        
        return redirect()->back()->with('success', 'Listing deleted successfully');
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'You have been logged out successfully');
    }
}