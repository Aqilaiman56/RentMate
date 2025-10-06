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
        $locations = Location::all();
        
        // If no locations exist, create empty collection
        if ($locations->isEmpty()) {
            $locations = collect([]);
        }
        
        // Start building the query
        $query = Item::with(['location', 'category', 'user'])
            ->where('Availability', '!=', 0); // Only show available items
        
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
        $items = $query->orderBy('DateAdded', 'desc')->paginate(12);
        
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
        
        return view('user.HomePage', compact('items', 'categories', 'locations'));
    }
}