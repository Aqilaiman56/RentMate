<?php

namespace App\Http\Controllers;
use App\Models\Item;
use Illuminate\Http\Request;


class ItemController extends Controller
{
public function index(Request $request)
{
    $search = $request->input('search');

    $items = Item::with('location') // eager load Location model
        ->when($search, function ($query, $search) {
            return $query->where('ItemName', 'like', "%{$search}%");
        })
        ->where('Availability', true)
        ->orderByDesc('DateAdded')
        ->paginate(9);

    return view('home', compact('items', 'search'));
}
}
