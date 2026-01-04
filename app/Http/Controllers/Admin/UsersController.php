<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    /**
     * Display all users
     */
    public function index(Request $request)
    {
        // Start query - show all users if filter is 'all', otherwise non-admin
        $query = User::query();

        // Filter by admin status
        if ($request->has('filter') && $request->filter !== 'all') {
            if ($request->filter === 'admin') {
                $query->where('IsAdmin', true);
            } elseif ($request->filter === 'regular') {
                $query->where('IsAdmin', false);
            } elseif ($request->filter === 'suspended') {
                $query->where('IsSuspended', true);
            }
        }

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('UserName', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('Email', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // User type filter
        if ($request->has('user_type') && $request->user_type != 'all') {
            $query->where('UserType', $request->user_type);
        }

        // Sort filter
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('CreatedAt', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('CreatedAt', 'asc');
                    break;
                case 'name-az':
                    $query->orderBy('UserName', 'asc');
                    break;
                case 'name-za':
                    $query->orderBy('UserName', 'desc');
                    break;
                default:
                    $query->orderBy('CreatedAt', 'desc');
            }
        } else {
            $query->orderBy('CreatedAt', 'desc');
        }

        // Paginate results
        $users = $query->paginate(20);

        // Calculate statistics
        $totalUsers = User::count();
        $activeUsers = User::where('CreatedAt', '>=', now()->subMonth())
            ->count();

        // Count by user type
        $adminCount = User::where('IsAdmin', 1)->count();
        $regularUserCount = User::where('IsAdmin', 0)->count();
        $suspendedCount = User::where('IsSuspended', 1)->count();

        return view('admin.users', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'adminCount',
            'regularUserCount',
            'suspendedCount'
        ));
    }

    /**
     * Show user details
     */
    public function show($id)
    {
        $user = User::where('UserID', $id)
            ->with(['items', 'bookings'])
            ->firstOrFail();

        // Get counts
        $itemsCount = $user->items()->count();
        $bookingsCount = $user->bookings()->count();
        $reviewsCount = $user->reviews()->count();

        return view('admin.users.show', compact('user', 'itemsCount', 'bookingsCount', 'reviewsCount'));
    }

    /**
     * Promote user to admin
     */
    public function promoteToAdmin($id)
    {
        $user = User::where('UserID', $id)->firstOrFail();

        // Check if already admin
        if ($user->IsAdmin) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This user is already an admin.'
                ], 400);
            }
            return back()->with('error', 'This user is already an admin.');
        }

        // Promote to admin
        $user->update(['IsAdmin' => true]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $user->UserName . ' has been promoted to admin.'
            ]);
        }

        return back()->with('success', $user->UserName . ' has been promoted to admin.');
    }

    /**
     * Demote admin to regular user
     */
    public function demoteFromAdmin($id)
    {
        $user = User::where('UserID', $id)->firstOrFail();

        // Check if already regular user
        if (!$user->IsAdmin) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This user is not an admin.'
                ], 400);
            }
            return back()->with('error', 'This user is not an admin.');
        }

        // Prevent demoting yourself
        if ($user->UserID === auth()->id()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot demote yourself from admin.'
                ], 400);
            }
            return back()->with('error', 'You cannot demote yourself from admin.');
        }

        // Count total admins
        $adminCount = User::where('IsAdmin', true)->count();

        // Prevent removing last admin
        if ($adminCount <= 1) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot demote the last admin. There must be at least one admin.'
                ], 400);
            }
            return back()->with('error', 'Cannot demote the last admin. There must be at least one admin.');
        }

        // Demote from admin
        $user->update(['IsAdmin' => false]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $user->UserName . ' has been demoted to regular user.'
            ]);
        }

        return back()->with('success', $user->UserName . ' has been demoted to regular user.');
    }

    /**
     * Delete a user
     */
    public function destroy($id)
    {
        $user = User::where('UserID', $id)
            ->where('IsAdmin', 0)
            ->firstOrFail();

        // Check if user has active bookings
        $hasActiveBookings = $user->bookings()
            ->where('Status', 'Approved')
            ->where('EndDate', '>=', now())
            ->exists();

        if ($hasActiveBookings) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete user with active bookings'
                ], 400);
            }
            return back()->with('error', 'Cannot delete user with active bookings');
        }

        // Delete profile image if exists
        if ($user->ProfileImage) {
            Storage::disk('public')->delete($user->ProfileImage);
        }

        // Delete user's items and their images
        foreach ($user->items as $item) {
            if ($item->ImagePath) {
                Storage::disk('public')->delete($item->ImagePath);
            }
            $item->delete();
        }

        $user->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        }

        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }

    /**
     * Export users data
     */
    public function export()
    {
        $users = User::where('IsAdmin', 0)->get();

        // Create CSV
        $filename = 'users_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['ID', 'Name', 'Email', 'User Type', 'Joined Date']);

            // Add data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->UserID,
                    $user->UserName,
                    $user->Email,
                    $user->UserType,
                    $user->CreatedAt ? $user->CreatedAt : 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Suspend a user
     */
    public function suspend(Request $request, $id)
    {
        $user = User::where('UserID', $id)
            ->where('IsAdmin', 0)
            ->firstOrFail();

        $validated = $request->validate([
            'duration' => 'required|in:7,30,90,permanent',
            'reason' => 'required|string|max:500'
        ]);

        $suspendedUntil = null;
        if ($validated['duration'] !== 'permanent') {
            $suspendedUntil = now()->addDays((int)$validated['duration']);
        }

        $user->update([
            'IsSuspended' => true,
            'SuspendedUntil' => $suspendedUntil,
            'SuspensionReason' => $validated['reason'],
            'SuspendedByAdminID' => auth()->id()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User suspended successfully'
            ]);
        }

        return back()->with('success', 'User suspended successfully');
    }

    /**
     * Unsuspend a user
     */
    public function unsuspend($id)
    {
        $user = User::where('UserID', $id)
            ->where('IsAdmin', 0)
            ->firstOrFail();

        $user->update([
            'IsSuspended' => false,
            'SuspendedUntil' => null,
            'SuspensionReason' => null,
            'SuspendedByAdminID' => null
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User unsuspended successfully'
            ]);
        }

        return back()->with('success', 'User unsuspended successfully');
    }


    /**
     * Get user activity log
     */
    public function activityLog($id)
    {
        $user = User::where('UserID', $id)
            ->where('IsAdmin', 0)
            ->with(['items', 'bookings', 'reviews', 'reportsMade', 'reportsReceived'])
            ->firstOrFail();

        // Compile activity data
        $activities = collect();

        // Add item listings
        foreach ($user->items as $item) {
            $activities->push([
                'type' => 'item_listed',
                'description' => 'Listed item: ' . $item->ItemName,
                'date' => $item->CreatedAt,
                'icon' => 'fa-box'
            ]);
        }

        // Add bookings
        foreach ($user->bookings as $booking) {
            $activities->push([
                'type' => 'booking',
                'description' => 'Booked item (Status: ' . $booking->Status . ')',
                'date' => $booking->CreatedAt,
                'icon' => 'fa-calendar-check'
            ]);
        }

        // Add reviews
        foreach ($user->reviews as $review) {
            $activities->push([
                'type' => 'review',
                'description' => 'Left a review (Rating: ' . $review->Rating . ')',
                'date' => $review->CreatedAt,
                'icon' => 'fa-star'
            ]);
        }

        // Add reports made
        foreach ($user->reportsMade as $report) {
            $activities->push([
                'type' => 'report_made',
                'description' => 'Reported: ' . $report->Reason,
                'date' => $report->DateReported,
                'icon' => 'fa-flag'
            ]);
        }

        // Add reports received
        foreach ($user->reportsReceived as $report) {
            $activities->push([
                'type' => 'report_received',
                'description' => 'Was reported: ' . $report->Reason,
                'date' => $report->DateReported,
                'icon' => 'fa-exclamation-triangle'
            ]);
        }

        // Sort by date descending
        $activities = $activities->sortByDesc('date')->values();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'activities' => $activities,
                'user' => [
                    'id' => $user->UserID,
                    'name' => $user->UserName,
                    'email' => $user->Email
                ]
            ]);
        }

        return view('admin.users.activity-log', compact('user', 'activities'));
    }
}