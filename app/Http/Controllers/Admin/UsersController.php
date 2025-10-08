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
        // Start query for non-admin users
        $query = User::where('IsAdmin', 0);

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
        $totalUsers = User::where('IsAdmin', 0)->count();
        $activeUsers = User::where('IsAdmin', 0)
            ->where('CreatedAt', '>=', now()->subMonth())
            ->count();
        
        // Count by user type
        $adminCount = User::where('IsAdmin', 1)->count();
        $regularUserCount = User::where('IsAdmin', 0)->count();

        return view('admin.users', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'adminCount',
            'regularUserCount'
        ));
    }

    /**
     * Show user details
     */
    public function show($id)
    {
        $user = User::where('UserID', $id)
            ->where('IsAdmin', 0)
            ->with(['items', 'bookings'])
            ->firstOrFail();

        // Get counts
        $itemsCount = $user->items()->count();
        $bookingsCount = $user->bookings()->count();
        $reviewsCount = $user->reviews()->count();

        return view('admin.users.show', compact('user', 'itemsCount', 'bookingsCount', 'reviewsCount'));
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
}