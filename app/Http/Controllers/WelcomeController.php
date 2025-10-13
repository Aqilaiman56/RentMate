<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        // Get all categories
        $categories = Category::all();
        
        // Start query for featured items
        $query = Item::with(['user', 'location', 'category'])
            ->where('Availability', 1);
        
        // Filter by category if selected
        if ($request->has('category') && $request->category != '') {
            $query->where('CategoryID', $request->category);
            $selectedCategory = Category::find($request->category);
        } else {
            $selectedCategory = null;
        }
        
        // Get items (limit to 12)
        $featuredItems = $query->orderBy('DateAdded', 'desc')
            ->limit(12)
            ->get();
        
        return view('welcome', compact('categories', 'featuredItems', 'selectedCategory'));
    }
}