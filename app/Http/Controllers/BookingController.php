<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Item;
use App\Models\Deposit;
use App\Models\Tax;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Show booking confirmation before payment
     */
    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,ItemID',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date'
        ]);

        $item = Item::with(['user', 'location', 'category'])->findOrFail($validated['item_id']);

        // Check if item is available
        if (!$item->Availability) {
            return back()->with('error', 'This item is not available for booking.');
        }

        // Check if user is trying to book their own item
        if ($item->UserID == auth()->id()) {
            return back()->with('error', 'You cannot book your own item.');
        }

        // Calculate rental period
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $days = $startDate->diffInDays($endDate);

        if ($days < 1) {
            $days = 1;
        }

        // Calculate amounts
        $rentalAmount = $item->PricePerDay * $days;
        $depositAmount = $item->DepositAmount;
        $taxAmount = 1.00;
        $totalAmount = $depositAmount + $taxAmount;

        // Prepare booking data
        $bookingData = [
            'item' => $item,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'days' => $days,
            'rental_amount' => $rentalAmount,
            'deposit_amount' => $depositAmount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount
        ];

        return view('bookings.confirm', $bookingData);
    }

    /**
     * Store a new booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,ItemID',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date'
        ]);

        $item = Item::findOrFail($validated['item_id']);

        // Check if item is available
        if (!$item->Availability) {
            return back()->with('error', 'This item is not available for booking.');
        }

        // Check if user is trying to book their own item
        if ($item->UserID == auth()->id()) {
            return back()->with('error', 'You cannot book your own item.');
        }

        // Calculate rental period
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $days = $startDate->diffInDays($endDate);

        if ($days < 1) {
            $days = 1;
        }

        // Calculate total amount
        $totalAmount = $item->PricePerDay * $days;

        try {
            DB::beginTransaction();

            // Create booking
            $booking = Booking::create([
                'UserID' => auth()->id(),
                'ItemID' => $item->ItemID,
                'StartDate' => $startDate,
                'EndDate' => $endDate,
                'TotalAmount' => $totalAmount,
                'DepositAmount' => $item->DepositAmount,
                'Status' => 'pending',
                'BookingDate' => now()
            ]);

            // Create deposit record
            Deposit::create([
                'BookingID' => $booking->BookingID,
                'Amount' => $item->DepositAmount,
                'Status' => 'held',
                'DateCollected' => now()
            ]);

            // Create tax record
            Tax::create([
                'UserID' => auth()->id(),
                'BookingID' => $booking->BookingID,
                'TaxAmount' => 1.00,
                'DateCollected' => now()
            ]);

            // Create notification for item owner
            Notification::create([
                'UserID' => $item->UserID,
                'Type' => 'booking',
                'Title' => 'New Booking Request',
                'Content' => auth()->user()->UserName . ' requested to book your item: ' . $item->ItemName,
                'RelatedID' => $booking->BookingID,
                'RelatedType' => 'booking',
                'CreatedAt' => now()
            ]);

            DB::commit();

            return redirect()->route('booking.show', $booking->BookingID)
                ->with('success', 'Booking created successfully! Please proceed with payment.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking creation error: ' . $e->getMessage());
            return back()->with('error', 'Failed to create booking. Please try again.');
        }
    }

    /**
     * Display a specific booking
     */
    public function show($id)
    {
        $booking = Booking::with(['item.user', 'item.location', 'user', 'deposit', 'payment'])
            ->findOrFail($id);

        // Check if user owns this booking or owns the item
        if ($booking->UserID !== auth()->id() && $booking->item->UserID !== auth()->id()) {
            abort(403, 'Unauthorized access to this booking');
        }

        return view('bookings.show', compact('booking'));
    }

    /**
     * Display user's bookings
     */
    public function userBookings()
    {
        $bookings = Booking::with(['item', 'item.location', 'payment'])
            ->where('UserID', auth()->id())
            ->orderBy('BookingDate', 'desc')
            ->paginate(10);

        return view('user.bookings', compact('bookings'));
    }

    /**
     * Cancel a booking
     */
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        // Only allow cancellation by the person who made the booking
        if ($booking->UserID !== auth()->id()) {
            abort(403, 'You are not authorized to cancel this booking.');
        }

        // Only allow cancellation if payment hasn't been made
        if ($booking->Status !== 'pending') {
            return back()->with('error', 'Cannot cancel a confirmed booking. Please contact support.');
        }

        $booking->update(['Status' => 'cancelled']);

        // Update deposit status
        if ($booking->deposit) {
            $booking->deposit->update(['Status' => 'cancelled']);
        }

        // Notify item owner
        Notification::create([
            'UserID' => $booking->item->UserID,
            'Type' => 'booking',
            'Title' => 'Booking Cancelled',
            'Content' => auth()->user()->UserName . ' cancelled their booking for ' . $booking->item->ItemName,
            'RelatedID' => $booking->BookingID,
            'RelatedType' => 'booking',
            'CreatedAt' => now()
        ]);

        return redirect()->route('user.bookings')
            ->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Complete a booking and refund deposit
     */
    public function complete($id)
    {
        $booking = Booking::with(['item', 'deposit', 'user'])->findOrFail($id);

        // Only item owner can complete
        if ($booking->item->UserID !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only confirmed bookings can be completed
        if ($booking->Status !== 'confirmed') {
            return back()->with('error', 'Only confirmed bookings can be marked as completed.');
        }

        // Check if booking period has ended
        if (now()->lt($booking->EndDate)) {
            return back()->with('error', 'Cannot complete booking before the rental period ends.');
        }

        DB::beginTransaction();
        try {
            // Mark booking as completed
            $booking->update(['Status' => 'completed']);

            // Refund deposit
            if ($booking->deposit) {
                $booking->deposit->update([
                    'Status' => 'refunded',
                    'DateRefunded' => now(),
                    'RefundMethod' => 'Auto Refund',
                    'RefundReference' => 'REF-' . $booking->BookingID . '-' . time()
                ]);
            }

            // Notify renter about completion and refund
            Notification::create([
                'UserID' => $booking->UserID,
                'Type' => 'booking',
                'Title' => '✅ Booking Completed - Deposit Refunded',
                'Content' => 'Your booking for ' . $booking->item->ItemName . ' has been completed. Your deposit of RM ' . number_format($booking->DepositAmount, 2) . ' will be refunded to your account within 3-5 business days.',
                'RelatedID' => $booking->BookingID,
                'RelatedType' => 'booking',
                'CreatedAt' => now()
            ]);

            // Notify owner
            Notification::create([
                'UserID' => $booking->item->UserID,
                'Type' => 'booking',
                'Title' => 'Booking Completed',
                'Content' => 'Booking #' . $booking->BookingID . ' has been marked as completed.',
                'RelatedID' => $booking->BookingID,
                'RelatedType' => 'booking',
                'CreatedAt' => now()
            ]);

            DB::commit();

            return back()->with('success', 'Booking completed successfully. Deposit refund has been processed.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking completion error: ' . $e->getMessage());
            return back()->with('error', 'Failed to complete booking. Please try again.');
        }
    }

    /**
     * Auto-complete bookings and refund deposits (run via cron)
     */
    public function autoCompleteBookings()
    {
        // Get all confirmed bookings where end date has passed
        $bookings = Booking::with(['item', 'deposit', 'user'])
            ->where('Status', 'confirmed')
            ->where('EndDate', '<', now())
            ->get();

        $completed = 0;

        foreach ($bookings as $booking) {
            DB::beginTransaction();
            try {
                // Mark as completed
                $booking->update(['Status' => 'completed']);

                // Refund deposit
                if ($booking->deposit) {
                    $booking->deposit->update([
                        'Status' => 'refunded',
                        'DateRefunded' => now(),
                        'RefundMethod' => 'Auto Refund',
                        'RefundReference' => 'AUTO-REF-' . $booking->BookingID . '-' . time()
                    ]);
                }

                // Notify user
                Notification::create([
                    'UserID' => $booking->UserID,
                    'Type' => 'booking',
                    'Title' => '✅ Booking Auto-Completed - Deposit Refunded',
                    'Content' => 'Your booking for ' . $booking->item->ItemName . ' has been automatically completed. Your deposit of RM ' . number_format($booking->DepositAmount, 2) . ' will be refunded within 3-5 business days.',
                    'RelatedID' => $booking->BookingID,
                    'RelatedType' => 'booking',
                    'CreatedAt' => now()
                ]);

                DB::commit();
                $completed++;

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Auto-complete booking error: ' . $e->getMessage());
            }
        }

        \Log::info("Auto-completed {$completed} bookings");
        return $completed;
    }
}