<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ListingsController extends Controller
{
    /**
     * Display all listings
     */
    public function index(Request $request)
    {
        // Start query
        $query = Item::with(['user', 'category', 'location', 'bookings', 'images']);

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('ItemName', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function($q) use ($searchTerm) {
                      $q->where('UserName', 'LIKE', '%' . $searchTerm . '%');
                  });
            });
        }

        // Category filter
        if ($request->has('category') && $request->category != 'all') {
            $query->where('CategoryID', $request->category);
        }

        // Status filter (based on Availability)
        if ($request->has('status') && $request->status != 'all') {
            if ($request->status == 'active') {
                $query->where('Availability', 1);
            } elseif ($request->status == 'unavailable') {
                $query->where('Availability', 0);
            }
        }

        // Sort filter
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('DateAdded', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('DateAdded', 'asc');
                    break;
                case 'price-high':
                    $query->orderBy('PricePerDay', 'desc');
                    break;
                case 'price-low':
                    $query->orderBy('PricePerDay', 'asc');
                    break;
                default:
                    $query->orderBy('DateAdded', 'desc');
            }
        } else {
            $query->orderBy('DateAdded', 'desc');
        }

        // Paginate results
        $items = $query->paginate(12);

        // Calculate statistics
        $totalListings = Item::count();
        $activeListings = Item::where('Availability', 1)->count();
        $unavailableListings = Item::where('Availability', 0)->count();
        $totalDeposits = Item::sum('DepositAmount');

        // Get all categories for filter
        $categories = Category::all();

        return view('admin.listings', compact(
            'items',
            'totalListings',
            'activeListings',
            'unavailableListings',
            'totalDeposits',
            'categories'
        ));
    }

    /**
     * Delete a listing
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        
        // Check if item has active bookings
        $hasActiveBookings = $item->bookings()
            ->where('Status', 'Approved')
            ->where('EndDate', '>=', now())
            ->exists();
        
        if ($hasActiveBookings) {
            return back()->with('error', 'Cannot delete item with active bookings');
        }
        
        // Delete image
        if ($item->ImagePath) {
            Storage::disk('public')->delete($item->ImagePath);
        }
        
        $item->delete();
        
        return redirect()->back()->with('success', 'Listing deleted successfully');
    }

    /**
     * Export listings data
     */
    public function export()
    {
        $items = Item::with(['user', 'category'])->get();

        // Create CSV
        $filename = 'listings_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($items) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['ID', 'Item Name', 'Category', 'Owner', 'Price/Day', 'Deposit', 'Status', 'Date Added']);
            
            // Add data
            foreach ($items as $item) {
                fputcsv($file, [
                    $item->ItemID,
                    $item->ItemName,
                    $item->category->CategoryName ?? 'N/A',
                    $item->user->UserName ?? 'N/A',
                    $item->PricePerDay,
                    $item->DepositAmount,
                    $item->Availability ? 'Active' : 'Unavailable',
                    $item->DateAdded ? $item->DateAdded : 'N/A',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}