<?php

namespace App\Http\Controllers;

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

class AdminController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function dashboard()
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
            ->join('items', 'bookings.ItemID', '=', 'items.ItemID')
            ->sum(DB::raw('DATEDIFF(bookings.EndDate, bookings.StartDate) * items.PricePerDay'));
        $totalTaxAmount = $totalBookingRevenue * 0.06; // 6% tax

        return view('admin.dashboard', compact(
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
     * Display all users
     */
    public function users()
    {
        $users = User::where('IsAdmin', 0)
            ->withCount(['items', 'bookings'])
            ->orderBy('CreatedAt', 'desc')
            ->paginate(20);

        $totalUsers = User::where('IsAdmin', 0)->count();
        $activeUsers = User::where('IsAdmin', 0)
            ->where('CreatedAt', '>=', now()->subMonth())
            ->count();
        $usersWithListings = User::where('IsAdmin', 0)
            ->has('items')
            ->count();

        return view('admin.users', compact(
            'users', 
            'totalUsers', 
            'activeUsers',
            'usersWithListings'
        ));
    }

    /**
     * Display all listings
     */
    public function listings()
    {
        $listings = Item::with(['user', 'category', 'location', 'bookings'])
            ->withCount('bookings')
            ->orderBy('DateAdded', 'desc')
            ->paginate(20);

        $totalListings = Item::count();
        $availableListings = Item::where('Availability', 1)->count();
        $totalValue = Item::sum('DepositAmount');
        $avgPricePerDay = Item::avg('PricePerDay');

        return view('admin.listings', compact(
            'listings',
            'totalListings',
            'availableListings',
            'totalValue',
            'avgPricePerDay'
        ));
    }

    /**
     * Display all deposits
     */
    public function deposits()
    {
        $deposits = Item::with(['user', 'category', 'location'])
            ->whereNotNull('DepositAmount')
            ->where('DepositAmount', '>', 0)
            ->orderBy('DepositAmount', 'desc')
            ->paginate(20);

        $totalDeposits = Item::sum('DepositAmount');
        $averageDeposit = Item::avg('DepositAmount');
        $depositCount = Item::where('DepositAmount', '>', 0)->count();
        $highestDeposit = Item::max('DepositAmount');

        return view('admin.deposits', compact(
            'deposits',
            'totalDeposits',
            'averageDeposit',
            'depositCount',
            'highestDeposit'
        ));
    }

    /**
     * Display all reports
     */
    public function reports()
    {
        $reports = Penalty::with([
            'reportedBy:UserID,UserName,Email,ProfileImage',
            'reportedUser:UserID,UserName,Email,ProfileImage',
            'item:ItemID,ItemName,ImagePath',
            'booking',
            'approvedByAdmin:UserID,UserName'
        ])
        ->orderBy('DateReported', 'desc')
        ->paginate(20);

        $totalReports = Penalty::count();
        $pendingReports = Penalty::where('ResolvedStatus', 0)->count();
        $resolvedReports = Penalty::where('ResolvedStatus', 1)->count();
        $rejectedReports = Penalty::where('ResolvedStatus', 1)
            ->where('PenaltyAmount', 0)
            ->count();

        return view('admin.reports', compact(
            'reports',
            'totalReports',
            'pendingReports',
            'resolvedReports',
            'rejectedReports'
        ));
    }

    /**
     * Display all penalties
     */
    public function penalties()
    {
        $penalties = Penalty::with([
            'reportedBy:UserID,UserName,Email,ProfileImage',
            'reportedUser:UserID,UserName,Email,ProfileImage',
            'item:ItemID,ItemName,ImagePath',
            'booking',
            'approvedByAdmin:UserID,UserName'
        ])
        ->whereNotNull('PenaltyAmount')
        ->where('PenaltyAmount', '>', 0)
        ->orderBy('DateReported', 'desc')
        ->paginate(20);

        $totalPenalties = Penalty::whereNotNull('PenaltyAmount')
            ->where('PenaltyAmount', '>', 0)
            ->count();
        $totalPenaltyAmount = Penalty::sum('PenaltyAmount') ?? 0;
        $averagePenalty = Penalty::where('PenaltyAmount', '>', 0)->avg('PenaltyAmount') ?? 0;
        $highestPenalty = Penalty::max('PenaltyAmount') ?? 0;

        return view('admin.penalties', compact(
            'penalties',
            'totalPenalties',
            'totalPenaltyAmount',
            'averagePenalty',
            'highestPenalty'
        ));
    }

    /**
     * Display tax information
     */
    public function taxes()
    {
        // Get all approved bookings with calculated amounts
        $taxTransactions = Booking::where('Status', 'Approved')
            ->with(['user:UserID,UserName,Email', 'item:ItemID,ItemName,PricePerDay'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Calculate tax details
        $taxCount = Booking::where('Status', 'Approved')->count();
        
        $bookings = Booking::where('Status', 'Approved')
            ->join('items', 'bookings.ItemID', '=', 'items.ItemID')
            ->select(
                DB::raw('SUM(DATEDIFF(bookings.EndDate, bookings.StartDate) * items.PricePerDay) as total_revenue')
            )
            ->first();
        
        $totalRevenue = $bookings->total_revenue ?? 0;
        $totalTaxAmount = $totalRevenue * 0.06; // 6% tax
        $averageTaxPerTransaction = $taxCount > 0 ? $totalTaxAmount / $taxCount : 0;

        return view('admin.taxes', compact(
            'taxTransactions',
            'taxCount',
            'totalTaxAmount',
            'totalRevenue',
            'averageTaxPerTransaction'
        ));
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
        $hasActiveBookings = $item->bookings()
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