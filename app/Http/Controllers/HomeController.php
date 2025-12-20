<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use App\Models\Wishlist;

class HomeController extends Controller
{
    /**
     * Display the home page with all items
     */
    public function index(Request $request)
    {
        // Get all categories for the navigation
        $categories = Category::all();
        
        // Get all locations for the search dropdown (with fallback)
        try {
            $locations = Location::all();
        } catch (\Exception $e) {
            // If Location model doesn't exist or table missing, create empty collection
            $locations = collect([]);
        }
        
        // Debug: Check what we have
        // dd($categories, $locations); // Uncomment this line to debug
        
        // Start building the query - Only show items marked as available by owner
        // Unavailable items will still appear in search results
        $query = Item::with(['location', 'category', 'user', 'images'])
            ->where('Availability', true);
        
        // Filter by category if selected
        if ($request->has('category') && $request->category != '') {
            $query->where('CategoryID', $request->category);
        }
        
        // Filter by search term (item name or description)
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('ItemName', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('Description', 'LIKE', '%' . $request->search . '%');
            });
        }
        
        // Filter by location
        if ($request->has('location') && $request->location != '') {
            $query->where('LocationID', $request->location);
        }
        
        // Filter by availability date
        if ($request->has('availability') && $request->availability != '') {
            // You'll need to check against your booking table
            // to see if the item is available on that date
            $date = $request->availability;
            
            $query->whereDoesntHave('bookings', function($q) use ($date) {
                $q->where('StartDate', '<=', $date)
                  ->where('EndDate', '>=', $date)
                  ->where('Status', 'Approved');
            });
        }
        
        // Get items with pagination
        if (!$request->has('category') || $request->category == '') {
            // For "All" items, order by top rented (bookings count) and user preferences (wishlist)
            if (auth()->check()) {
                // For logged-in users: prioritize wishlist items, then by bookings count
                $items = $query->leftJoin('wishlist', function($join) {
                    $join->on('items.ItemID', '=', 'wishlist.ItemID')
                         ->where('wishlist.UserID', '=', auth()->id());
                })
                ->select('items.*', \DB::raw('wishlist.ItemID IS NOT NULL as is_wishlist'))
                ->withCount('bookings')
                ->orderBy('is_wishlist', 'desc')
                ->orderBy('bookings_count', 'desc')
                ->paginate(12);
            } else {
                // For non-logged-in users: order by bookings count
                $items = $query->withCount('bookings')
                    ->orderBy('bookings_count', 'desc')
                    ->paginate(12);
            }
        } else {
            // For specific categories, keep the current ordering by date added
            $items = $query->orderBy('DateAdded', 'desc')->paginate(12);
        }
        
        // Check if items are in user's wishlist (if user is logged in)
        if (auth()->check()) {
            $wishlistItems = Wishlist::where('UserID', auth()->id())
                ->pluck('ItemID')
                ->toArray();
            
            foreach ($items as $item) {
                $item->isInWishlist = in_array($item->ItemID, $wishlistItems);
            }
        } else {
            foreach ($items as $item) {
                $item->isInWishlist = false;
            }
        }
        
        // IMPORTANT: Pass all variables to the view
        return view('user.HomePage', [
            'items' => $items,
            'categories' => $categories,
            'locations' => $locations
        ]);
    }
}