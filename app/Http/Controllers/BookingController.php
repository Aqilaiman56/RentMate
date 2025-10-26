<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Item;
use App\Models\Deposit;
use App\Models\Tax;
use App\Models\Notification;
use App\Models\Payment;
use App\Services\ToyyibPayService;
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

        $item = Item::with(['user', 'location', 'category', 'images'])->findOrFail($validated['item_id']);

        // Check if item is available
        if (!$item->Availability) {
            return back()->with('error', 'This item is not available for booking.');
        }

        // Check if user is trying to book their own item
        if ($item->UserID == auth()->id()) {
            return back()->with('error', 'You cannot book your own item.');
        }

        // Check if item is available for the selected dates
        if (!$item->isAvailableForDates($validated['start_date'], $validated['end_date'])) {
            return back()->with('error', 'This item is already booked for the selected dates. Please choose different dates.');
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

        // Check date availability
        if (!$item->isAvailableForDates($validated['start_date'], $validated['end_date'])) {
            return back()->with('error', 'This item is already booked for the selected dates.');
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
                'Status' => 'pending', // Changed to pending for payment flow
                'BookingDate' => now()
            ]);

            // Create deposit record
            Deposit::create([
                'BookingID' => $booking->BookingID,
                'DepositAmount' => $item->DepositAmount,
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

            // Update item availability automatically
            $item->updateAvailabilityStatus();

            // Create notification for item owner
            Notification::create([
                'UserID' => $item->UserID,
                'Type' => 'booking',
                'Title' => 'New Booking Request',
                'Content' => auth()->user()->UserName . ' booked your item: ' . $item->ItemName,
                'RelatedID' => $booking->BookingID,
                'RelatedType' => 'booking',
                'CreatedAt' => now()
            ]);

            DB::commit();

            return redirect()->route('booking.show', $booking->BookingID)
                ->with('success', 'Booking created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking creation error: ' . $e->getMessage());
            return back()->with('error', 'Failed to create booking. Please try again.');
        }
    }

    /**
     * Create booking and redirect to payment
     */
    public function createAndPay(Request $request)
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

        // Check date availability
        if (!$item->isAvailableForDates($validated['start_date'], $validated['end_date'])) {
            return back()->with('error', 'This item is already booked for the selected dates.');
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
                'TotalPaid' => 0, // Will be updated after payment
                'TaxAmount' => 1.00,
                'Status' => 'pending',
                'BookingDate' => now()
            ]);

            // Create deposit record
            Deposit::create([
                'BookingID' => $booking->BookingID,
                'DepositAmount' => $item->DepositAmount,
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

            // Update item availability automatically
            $item->updateAvailabilityStatus();

            // Create notification for item owner
            Notification::create([
                'UserID' => $item->UserID,
                'Type' => 'booking',
                'Title' => 'New Booking Request',
                'Content' => auth()->user()->UserName . ' booked your item: ' . $item->ItemName,
                'RelatedID' => $booking->BookingID,
                'RelatedType' => 'booking',
                'CreatedAt' => now()
            ]);

            DB::commit();

            // Create payment and redirect to ToyyibPay
            $toyyibpay = app(ToyyibPayService::class);

            // Calculate payment amount (Deposit + Tax)
            $depositAmount = $item->DepositAmount;
            $taxAmount = 1.00;
            $totalAmount = $depositAmount + $taxAmount;

            // Create payment record
            $payment = Payment::create([
                'BookingID' => $booking->BookingID,
                'Amount' => $totalAmount,
                'Status' => 'pending',
                'CreatedAt' => now()
            ]);

            // Prepare data for ToyyibPay
            $dateRange = '';
            if ($booking->StartDate && $booking->EndDate) {
                $dateRange = ' (Rental: ' . $booking->StartDate->format('d M') . ' - ' . $booking->EndDate->format('d M Y') . ')';
            }

            $billData = [
                'booking_id' => $booking->BookingID,
                'bill_name' => 'Security Deposit - Booking #' . $booking->BookingID,
                'bill_description' => 'Security deposit for ' . $item->ItemName . $dateRange . '. Rental fee to be paid directly to owner.',
                'amount' => $totalAmount,
                'payer_name' => auth()->user()->UserName,
                'payer_email' => auth()->user()->Email,
                'payer_phone' => auth()->user()->PhoneNumber ?? '',
            ];

            // Create bill in ToyyibPay
            $result = $toyyibpay->createBill($billData);

            if ($result['success']) {
                // Update payment with bill code
                $payment->update([
                    'BillCode' => $result['bill_code']
                ]);

                // Redirect to ToyyibPay payment page
                return redirect($result['payment_url']);
            } else {
                $payment->update(['Status' => 'failed']);

                return redirect()->route('booking.show', $booking->BookingID)
                    ->with('error', 'Failed to create payment. Please try again.');
            }

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
        $booking = Booking::with(['item.user', 'item.location', 'item.images', 'user', 'deposit', 'payment'])
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
        $bookings = Booking::with(['item', 'item.location', 'item.images', 'payment'])
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

        // Only allow cancellation if not completed
        if ($booking->Status === 'completed') {
            return back()->with('error', 'Cannot cancel a completed booking.');
        }

        DB::beginTransaction();
        try {
            $booking->update(['Status' => 'cancelled']);

            // Update deposit status
            if ($booking->deposit) {
                $booking->deposit->update(['Status' => 'refunded']);
            }

            // Update item availability
            $booking->item->updateAvailabilityStatus();

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

            DB::commit();

            return redirect()->route('user.bookings')
                ->with('success', 'Booking cancelled successfully. Your deposit will be refunded.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel booking.');
        }
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
        if (!in_array($booking->Status, ['confirmed', 'Confirmed', 'ongoing', 'Ongoing'])) {
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

            // Update item availability
            $booking->item->updateAvailabilityStatus();

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

            return back()->with('success', 'Booking completed successfully. Item is now available again. Deposit refund has been processed.');

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
            ->whereIn('Status', ['confirmed', 'Confirmed', 'ongoing', 'Ongoing'])
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

                // Update item availability
                $booking->item->updateAvailabilityStatus();

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

        \Log::info("Auto-completed {$completed} bookings and updated item availability");
        return $completed;
    }
}