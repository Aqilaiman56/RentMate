<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Item;

class WishlistController extends Controller
{
    /**
     * Toggle item in wishlist
     */
    public function toggle($itemId)
    {
        $userId = auth()->id();
        
        // Check if item exists in wishlist
        $wishlist = Wishlist::where('UserID', $userId)
            ->where('ItemID', $itemId)
            ->first();
        
        if ($wishlist) {
            // Remove from wishlist
            $wishlist->delete();
            return response()->json([
                'success' => true,
                'added' => false,
                'message' => 'Item removed from wishlist'
            ]);
        } else {
            // Add to wishlist
            Wishlist::create([
                'UserID' => $userId,
                'ItemID' => $itemId,
                'DateAdded' => now()
            ]);
            return response()->json([
                'success' => true,
                'added' => true,
                'message' => 'Item added to wishlist'
            ]);
        }
    }
    
    /**
     * Add item to wishlist
     */
    public function add($itemId)
    {
        $userId = auth()->id();
        
        // Check if already in wishlist
        $exists = Wishlist::where('UserID', $userId)
            ->where('ItemID', $itemId)
            ->exists();
        
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Item already in wishlist'
            ], 400);
        }
        
        Wishlist::create([
            'UserID' => $userId,
            'ItemID' => $itemId,
            'DateAdded' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Item added to wishlist'
        ]);
    }
    
    
    /**
     * Remove item from wishlist
     */
    public function remove($itemId)
    {
        $userId = auth()->id();
        
        $deleted = Wishlist::where('UserID', $userId)
            ->where('ItemID', $itemId)
            ->delete();
        
        if ($deleted) {
            // Check if it's an AJAX request
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from wishlist'
                ]);
            }
            
            return redirect()->route('wishlist.index')
                ->with('success', 'Item removed from wishlist');
        }
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in wishlist'
            ], 404);
        }
        
        return redirect()->route('wishlist.index')
            ->with('error', 'Item not found in wishlist');
    }
    

    
    /**
     * Display user's wishlist
     */
    public function index()
    {
        $userId = auth()->id();
        
        $wishlistItems = Wishlist::where('UserID', $userId)
            ->with('item.location', 'item.category')
            ->orderBy('DateAdded', 'desc')
            ->get();
        
        return view('user.wishlist', compact('wishlistItems')); 
    }
}