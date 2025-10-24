<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Display the welcome page with items, search, and category filtering
     */
    public function index(Request $request)
    {
        // Get all categories for the category filter
        $categories = Category::all();
        $selectedCategory = null;
        
        // Start building the query
        $query = Item::with(['category', 'location', 'images'])
            ->where('Availability', true);
        
        // Handle category filter
        if ($request->has('category') && $request->category != '') {
            $selectedCategory = Category::find($request->category);
            if ($selectedCategory) {
                $query->where('CategoryID', $request->category);
            }
        }
        
        // Handle search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            
            $query->where(function($q) use ($searchTerm) {
                // Search in item name
                $q->where('ItemName', 'like', '%' . $searchTerm . '%')
                  // Search in description
                  ->orWhere('Description', 'like', '%' . $searchTerm . '%')
                  // Search in category name
                  ->orWhereHas('category', function($q) use ($searchTerm) {
                      $q->where('CategoryName', 'like', '%' . $searchTerm . '%');
                  })
                  // Search in location (using LocationName instead of City)
                  ->orWhereHas('location', function($q) use ($searchTerm) {
                      $q->where('LocationName', 'like', '%' . $searchTerm . '%');
                  });
            });
        }
        
        // Get items with pagination
        $featuredItems = $query->latest('DateAdded')->paginate(12);
        
        // Return the welcome view
        return view('welcome', compact('categories', 'featuredItems', 'selectedCategory'));
    }
}