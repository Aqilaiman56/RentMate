<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;

class WelcomeController extends Controller
{
    public function index()
    {
        // Get all categories
        $categories = Category::all();
        
        // Get featured items (available items, limit to 8)
        $featuredItems = Item::with(['user', 'location', 'category'])
            ->where('Availability', 1)
            ->orderBy('DateAdded', 'desc')
            ->limit(8)
            ->get();
        
        return view('welcome', compact('categories', 'featuredItems'));
    }
}