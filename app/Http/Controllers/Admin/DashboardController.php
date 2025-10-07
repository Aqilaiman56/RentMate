<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Models\Penalty;
use App\Models\Booking;
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
        // Get statistics
        $totalUsers = User::where('IsAdmin', 0)->count();
        $totalListings = Item::count();
        $totalDeposits = Item::sum('DepositAmount');
        $totalReports = Penalty::count();
        $pendingReports = Penalty::where('ResolvedStatus', 0)->count();
        $totalPenalties = Penalty::whereNotNull('PenaltyAmount')
            ->where('PenaltyAmount', '>', 0)
            ->count();
        $totalPenaltyAmount = Penalty::sum('PenaltyAmount') ?? 0;
        
        // Calculate tax from bookings (assuming 6% tax rate)
        $taxCount = Booking::where('Status', 'Approved')->count();
        $totalBookingRevenue = Booking::where('Status', 'Approved')
            ->join('items', 'booking.ItemID', '=', 'items.ItemID')
            ->sum(DB::raw('DATEDIFF(booking.EndDate, booking.StartDate) * items.PricePerDay'));
        $totalTaxAmount = $totalBookingRevenue * 0.06; // 6% tax

        return view('admin.AdminDashboard', compact(
            'totalUsers',
            'totalListings',
            'totalDeposits',
            'totalReports',
            'pendingReports',
            'totalPenalties',
            'totalPenaltyAmount',
            'taxCount',
            'totalTaxAmount'
        ));
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