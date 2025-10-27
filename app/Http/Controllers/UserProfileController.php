<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserProfileController extends Controller
{
    /**
     * Display a user's public profile
     */
    public function show($id)
    {
        $user = User::where('UserID', $id)
            ->with(['items' => function($query) {
                $query->where('Availability', 1)
                      ->latest()
                      ->take(6);
            }])
            ->firstOrFail();

        // Get statistics
        $totalListings = $user->items()->count();
        $activeListings = $user->items()->where('Availability', 1)->count();
        $totalBookings = $user->bookings()->count();
        $completedBookings = $user->bookings()->where('Status', 'completed')->count();

        // Calculate average rating from reviews received
        $averageRating = $user->items()
            ->withCount('reviews')
            ->with('reviews')
            ->get()
            ->pluck('reviews')
            ->flatten()
            ->avg('Rating') ?? 0;

        $totalReviews = $user->items()
            ->withCount('reviews')
            ->get()
            ->sum('reviews_count');

        // Get recent reviews
        $recentReviews = $user->items()
            ->with(['reviews' => function($query) {
                $query->with('user')
                      ->latest()
                      ->take(5);
            }])
            ->get()
            ->pluck('reviews')
            ->flatten()
            ->sortByDesc('CreatedAt')
            ->take(5);

        return view('user-profile', compact(
            'user',
            'totalListings',
            'activeListings',
            'totalBookings',
            'completedBookings',
            'averageRating',
            'totalReviews',
            'recentReviews'
        ));
    }
}
