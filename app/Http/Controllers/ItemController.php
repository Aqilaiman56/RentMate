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
     * Display item details page (AUTHENTICATED USERS)
     */
    public function show($id)
    {
        // Get item with all relationships
        $item = Item::with([
            'user',
            'location',
            'category',
            'reviews.user',
            'images',
            'bookings' => function($query) {
                $query->whereIn('Status', ['Confirmed', 'confirmed', 'Ongoing', 'ongoing']);
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
            ->whereIn('Status', ['Confirmed', 'confirmed', 'Ongoing', 'ongoing'])
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
     * Show public item details (FOR GUESTS - NO AUTH REQUIRED)
     */
    public function showPublicDetails($id)
    {
        $item = Item::with(['category', 'location', 'user', 'reviews.user', 'images'])
            ->findOrFail($id);
        
        $averageRating = $item->reviews()->avg('Rating') ?? 0;
        $totalReviews = $item->reviews()->count();
        
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingDistribution[$i] = $item->reviews()->where('Rating', $i)->count();
        }
        
        return view('public-item-details', compact('item', 'averageRating', 'totalReviews', 'ratingDistribution'));
    }
    
    /**
     * Show user's listed items (My Listings page)
     */
    public function myItems()
    {
        $items = Item::where('UserID', auth()->id())
            ->with(['location', 'category', 'bookings', 'reviews', 'images'])
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
            'Description' => 'required|string|min:50',
            'CategoryID' => 'required|exists:category,CategoryID',
            'LocationID' => 'required|exists:location,LocationID',
            'DepositAmount' => 'required|numeric|min:0|max:9999.99',
            'PricePerDay' => 'required|numeric|min:0|max:9999.99',
            'images' => 'required|array|min:1|max:4',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Quantity' => 'required|integer|min:1',
            'Availability' => 'nullable|boolean'
        ], [
            'DepositAmount.min' => 'Deposit amount cannot be negative. Please enter a valid amount.',
            'DepositAmount.max' => 'Deposit amount cannot exceed RM 9,999.99.',
            'PricePerDay.min' => 'Price per day cannot be negative. Please enter a valid price.',
            'PricePerDay.max' => 'Price per day cannot exceed RM 9,999.99.',
            'Quantity.min' => 'Quantity must be at least 1.',
            'DepositAmount.numeric' => 'Deposit amount must be a valid number.',
            'PricePerDay.numeric' => 'Price per day must be a valid number.',
            'Quantity.integer' => 'Quantity must be a whole number.'
        ]);

        $validated['UserID'] = auth()->id();
        $validated['DateAdded'] = now();
        $validated['Availability'] = $request->has('Availability') ? 1 : 1;
        $validated['AvailableQuantity'] = $validated['Quantity'];

        // Create the item
        $item = Item::create($validated);

        // Store all images in item_images table
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('items', 'public');
                $item->images()->create([
                    'ImagePath' => $imagePath,
                    'DisplayOrder' => $index
                ]);
            }
        }

        return redirect()->route('items.my')->with('success', 'Item listed successfully!');
    }
    
    /**
     * Show edit item form
     */
    public function edit($id)
    {
        $item = Item::with('images')
            ->where('ItemID', $id)
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
            'Description' => 'required|string|min:50',
            'CategoryID' => 'required|exists:category,CategoryID',
            'LocationID' => 'required|exists:location,LocationID',
            'DepositAmount' => 'required|numeric|min:0|max:9999.99',
            'PricePerDay' => 'required|numeric|min:0|max:9999.99',
            'images' => 'nullable|array|min:1|max:4',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Quantity' => 'required|integer|min:1',
            'Availability' => 'nullable|boolean'
        ], [
            'DepositAmount.min' => 'Deposit amount cannot be negative. Please enter a valid amount.',
            'DepositAmount.max' => 'Deposit amount cannot exceed RM 9,999.99.',
            'PricePerDay.min' => 'Price per day cannot be negative. Please enter a valid price.',
            'PricePerDay.max' => 'Price per day cannot exceed RM 9,999.99.',
            'Quantity.min' => 'Quantity must be at least 1.',
            'DepositAmount.numeric' => 'Deposit amount must be a valid number.',
            'PricePerDay.numeric' => 'Price per day must be a valid number.',
            'Quantity.integer' => 'Quantity must be a whole number.'
        ]);

        // Handle image upload if new images provided
        if ($request->hasFile('images')) {
            // Delete old images from storage
            foreach ($item->images as $oldImage) {
                Storage::disk('public')->delete($oldImage->ImagePath);
            }

            // Delete old image records
            $item->images()->delete();

            // Store all new images
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('items', 'public');
                $item->images()->create([
                    'ImagePath' => $imagePath,
                    'DisplayOrder' => $index
                ]);
            }
        }

        $validated['Availability'] = $request->has('Availability') ? 1 : 0;

        // Update available quantity if total quantity changed
        if ($validated['Quantity'] != $item->Quantity) {
            $bookedQuantity = $item->getBookedQuantity();
            $validated['AvailableQuantity'] = max(0, $validated['Quantity'] - $bookedQuantity);
        }

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
            ->whereIn('Status', ['Confirmed', 'confirmed', 'Ongoing', 'ongoing'])
            ->where('EndDate', '>=', now())
            ->exists();

        if ($hasActiveBookings) {
            return redirect()->route('items.my')
                ->with('error', 'Cannot delete item with active bookings');
        }

        // Delete related records
        $item->wishlists()->delete();
        $item->reviews()->delete();
        $item->bookings()->delete();

        // Delete all images from storage and records
        foreach ($item->images as $image) {
            Storage::disk('public')->delete($image->ImagePath);
        }
        $item->images()->delete();

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
            'Comment' => 'required|string|max:1000',
            'ReviewImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Check if user has booked this item
        $hasBooked = Booking::where('UserID', auth()->id())
            ->where('ItemID', $validated['ItemID'])
            ->whereIn('Status', ['Confirmed', 'confirmed', 'completed', 'Completed'])
            ->exists();

        if (!$hasBooked) {
            return back()->with('error', 'You can only review items you have rented');
        }

        // Check if already reviewed
        $existingReview = Review::where('UserID', auth()->id())
            ->where('ItemID', $validated['ItemID'])
            ->exists();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this item');
        }

        // Handle image upload
        $reviewImagePath = null;
        if ($request->hasFile('ReviewImage')) {
            $reviewImagePath = $request->file('ReviewImage')->store('review_images', 'public');
        }

        Review::create([
            'UserID' => auth()->id(),
            'ItemID' => $validated['ItemID'],
            'Rating' => $validated['Rating'],
            'Comment' => $validated['Comment'],
            'ReviewImage' => $reviewImagePath,
            'DatePosted' => now(),
            'IsReported' => false
        ]);

        return back()->with('success', 'Review added successfully!');
    }

    /**
     * View bookings for a specific item (Owner only)
     */
    public function viewItemBookings($id)
    {
        $item = Item::with(['user', 'location', 'category', 'images'])
            ->findOrFail($id);

        // Check if the authenticated user is the owner
        if ($item->UserID !== auth()->id()) {
            abort(403, 'Unauthorized access. You are not the owner of this item.');
        }

        // Get all bookings for this item with related data
        $bookings = Booking::with(['user', 'payment', 'deposit'])
            ->where('ItemID', $id)
            ->orderBy('BookingDate', 'desc')
            ->paginate(10);

        return view('user.item-bookings', compact('item', 'bookings'));
    }


}