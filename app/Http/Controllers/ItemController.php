<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Review;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display item details page
     */
    public function show($id)
    {
        // Get item with all relationships
        $item = Item::with([
            'user', 
            'location', 
            'category',
            'reviews.user',
            'bookings' => function($query) {
                $query->where('Status', 'Approved');
            }
        ])->findOrFail($id);
        
        // Calculate average rating
        $averageRating = $item->reviews()->avg('Rating') ?? 0;
        $totalReviews = $item->reviews()->count();
        
        // Get rating distribution (1-5 stars)
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingDistribution[$i] = $item->reviews()
                ->where('Rating', $i)
                ->count();
        }
        
        // Get booked dates for calendar
        $bookedDates = $item->bookings()
            ->where('Status', 'Approved')
            ->where('EndDate', '>=', now())
            ->get(['StartDate', 'EndDate'])
            ->map(function($booking) {
                return [
                    'start' => $booking->StartDate ? $booking->StartDate->format('Y-m-d') : null,
                    'end' => $booking->EndDate ? $booking->EndDate->format('Y-m-d') : null
                ];
            })
            ->filter(function($booking) {
                return $booking['start'] && $booking['end'];
            });
        
        return view('user.item-details', compact(
            'item', 
            'averageRating', 
            'totalReviews', 
            'ratingDistribution',
            'bookedDates'
        ));
    }
    
    /**
     * Show user's listed items (My Listings page)
     */
    public function myItems()
    {
        $items = Item::where('UserID', auth()->id())
            ->with(['location', 'category', 'bookings', 'reviews'])
            ->orderBy('DateAdded', 'desc')
            ->paginate(12);
        
        return view('user.listings', compact('items'));
    }
    
    /**
     * Show create item form
     */
    public function create()
    {
        $categories = Category::all();
        $locations = Location::all();
        
        return view('user.add-listing', compact('categories', 'locations'));
    }
    
    /**
     * Store new item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ItemName' => 'required|string|max:255',
            'Description' => 'required|string',
            'CategoryID' => 'required|exists:category,CategoryID',
            'LocationID' => 'required|exists:location,LocationID',
            'DepositAmount' => 'required|numeric|min:0',
            'PricePerDay' => 'required|numeric|min:0',
            'ImagePath' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Availability' => 'nullable|boolean'
        ]);
        
        // Handle image upload
        if ($request->hasFile('ImagePath')) {
            $imagePath = $request->file('ImagePath')->store('items', 'public');
            $validated['ImagePath'] = $imagePath;
        }
        
        $validated['UserID'] = auth()->id();
        $validated['DateAdded'] = now();
        $validated['Availability'] = $request->has('Availability') ? 1 : 1; // Default to available
        
        $item = Item::create($validated);
        
        return redirect()->route('items.my')->with('success', 'Item listed successfully!');
    }
    
    /**
     * Show edit item form
     */
    public function edit($id)
    {
        $item = Item::where('ItemID', $id)
            ->where('UserID', auth()->id())
            ->firstOrFail();
        
        $categories = Category::all();
        $locations = Location::all();
        
        return view('user.edit-listing', compact('item', 'categories', 'locations'));
    }
    
    /**
     * Update item
     */
    public function update(Request $request, $id)
    {
        $item = Item::where('ItemID', $id)
            ->where('UserID', auth()->id())
            ->firstOrFail();
        
        $validated = $request->validate([
            'ItemName' => 'required|string|max:255',
            'Description' => 'required|string',
            'CategoryID' => 'required|exists:category,CategoryID',
            'LocationID' => 'required|exists:location,LocationID',
            'DepositAmount' => 'required|numeric|min:0',
            'PricePerDay' => 'required|numeric|min:0',
            'ImagePath' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Availability' => 'nullable|boolean'
        ]);
        
        // Handle image upload if new image provided
        if ($request->hasFile('ImagePath')) {
            // Delete old image
            if ($item->ImagePath) {
                Storage::disk('public')->delete($item->ImagePath);
            }
            
            $imagePath = $request->file('ImagePath')->store('items', 'public');
            $validated['ImagePath'] = $imagePath;
        }
        
        $validated['Availability'] = $request->has('Availability') ? 1 : 0;
        
        $item->update($validated);
        
        return redirect()->route('items.my')->with('success', 'Item updated successfully!');
    }
    
    /**
     * Delete item
     */
    public function destroy($id)
    {
        $item = Item::where('ItemID', $id)
            ->where('UserID', auth()->id())
            ->firstOrFail();
        
        // Check if item has active bookings
        $hasActiveBookings = $item->bookings()
            ->where('Status', 'Approved')
            ->where('EndDate', '>=', now())
            ->exists();
        
        if ($hasActiveBookings) {
            return redirect()->route('items.my')
                ->with('error', 'Cannot delete item with active bookings');
        }
        
        // Delete related records first to avoid foreign key constraint errors
        
        // 1. Delete wishlist entries
        $item->wishlists()->delete();
        
        // 2. Delete reviews
        $item->reviews()->delete();
        
        // 3. Delete bookings (only past/cancelled ones since we checked for active ones above)
        $item->bookings()->delete();
        
        // 4. Delete messages related to this item (if you have a messages table with ItemID)
        // Uncomment the line below if you have messages relationship
        // $item->messages()->delete();
        
        // 5. Delete image from storage
        if ($item->ImagePath) {
            Storage::disk('public')->delete($item->ImagePath);
        }
        
        // 6. Finally delete the item itself
        $item->delete();
        
        return redirect()->route('items.my')->with('success', 'Item deleted successfully!');
    }

    /**
     * Show user's listings (for user dashboard)
     */
    public function userListings()
    {
        $items = Item::where('UserID', auth()->id())
            ->with(['location', 'category', 'bookings'])
            ->orderBy('DateAdded', 'desc')
            ->paginate(12);
        
        return view('user.listings', compact('items'));
    }
    
    /**
     * Add review to item
     */
    public function addReview(Request $request)
    {
        $validated = $request->validate([
            'ItemID' => 'required|exists:items,ItemID',
            'Rating' => 'required|integer|min:1|max:5',
            'Comment' => 'required|string|max:1000'
        ]);
        
        // Check if user has booked this item before
        $hasBooked = Booking::where('UserID', auth()->id())
            ->where('ItemID', $validated['ItemID'])
            ->where('Status', 'Approved')
            ->exists();
        
        if (!$hasBooked) {
            return back()->with('error', 'You can only review items you have rented');
        }
        
        // Check if user already reviewed this item
        $existingReview = Review::where('UserID', auth()->id())
            ->where('ItemID', $validated['ItemID'])
            ->exists();
        
        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this item');
        }
        
        Review::create([
            'UserID' => auth()->id(),
            'ItemID' => $validated['ItemID'],
            'Rating' => $validated['Rating'],
            'Comment' => $validated['Comment'],
            'DatePosted' => now(),
            'IsReported' => false
        ]);
        
        return back()->with('success', 'Review added successfully!');
    }
}