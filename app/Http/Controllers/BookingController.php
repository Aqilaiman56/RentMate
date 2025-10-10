<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Item;

class BookingController extends Controller
{
    /**
     * Display user's bookings
     */
    public function userBookings()
    {
        $bookings = Booking::where('UserID', auth()->id())
            ->with(['item.user', 'item.location'])
            ->orderBy('StartDate', 'desc')
            ->paginate(12);
        
        return view('user.bookings', compact('bookings'));
    }

    /**
     * Display all bookings (if you already have this)
     */
    public function index()
    {
        $bookings = Booking::where('UserID', auth()->id())
            ->with(['item'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Create a new booking
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'ItemID' => 'required|exists:items,ItemID',
            'StartDate' => 'required|date|after_or_equal:today',
            'EndDate' => 'required|date|after:StartDate',
        ]);

        $item = Item::findOrFail($validated['ItemID']);
        
        // Calculate total price
        $days = \Carbon\Carbon::parse($validated['StartDate'])
            ->diffInDays(\Carbon\Carbon::parse($validated['EndDate'])) + 1;
        $totalPrice = $days * $item->PricePerDay;

        $booking = Booking::create([
            'UserID' => auth()->id(),
            'ItemID' => $validated['ItemID'],
            'StartDate' => $validated['StartDate'],
            'EndDate' => $validated['EndDate'],
            'Status' => 'Pending',
            'TotalPrice' => $totalPrice,
        ]);

        return redirect()->route('user.bookings')->with('success', 'Booking request submitted!');
    }

        // Create tax record
        Tax::create([
            'BookingID' => $booking->BookingID,
            'UserID' => $booking->UserID,
            'TaxAmount' => 1.00,
            'DateCollected' => now(),
            'TaxType' => 'Booking Tax',
            'Description' => 'Tax for booking #' . $booking->BookingID,
        ]);

    /**
     * Show booking details
     */
    public function show($id)
    {
        $booking = Booking::where('BookingID', $id)
            ->where('UserID', auth()->id())
            ->with(['item.user', 'item.location'])
            ->firstOrFail();
        
        return view('booking.show', compact('booking'));
    }
}