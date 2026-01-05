<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ToyyibPayService;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $toyyibpay;

    public function __construct(ToyyibPayService $toyyibpay)
    {
        $this->toyyibpay = $toyyibpay;
    }

    /**
     * Create payment for booking (Deposit + Service Fee only)
     */
    public function createPayment(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:booking,BookingID'
        ]);

        $booking = Booking::with(['item', 'user'])->findOrFail($validated['booking_id']);

        // Check if payment already exists
        $existingPayment = Payment::where('BookingID', $booking->BookingID)
            ->where('Status', 'successful')
            ->first();

        if ($existingPayment) {
            return redirect()->route('booking.show', $booking->BookingID)
                ->with('error', 'Payment has already been made for this booking');
        }

        // Calculate amount (Deposit + Service Fee ONLY, no rental fee)
        $depositAmount = $booking->DepositAmount;
        $serviceFeeAmount = 1.00;
        $totalAmount = $depositAmount + $serviceFeeAmount;

        // Create payment record
        $payment = Payment::create([
            'BookingID' => $booking->BookingID,
            'Amount' => $totalAmount,
            'Status' => 'pending',
            'CreatedAt' => now()
        ]);

        // Prepare data for ToyyibPay
        $billData = [
            'booking_id' => $booking->BookingID,
            'bill_name' => 'Security Deposit - Booking #' . $booking->BookingID,
            'bill_description' => 'Security deposit for ' . $booking->item->ItemName . ' (Rental: ' . $booking->StartDate->format('d M') . ' - ' . $booking->EndDate->format('d M Y') . '). Rental fee to be paid directly to owner.',
            'amount' => $totalAmount,
            'payer_name' => $booking->user->UserName,
            'payer_email' => $booking->user->Email,
            'payer_phone' => $booking->user->PhoneNumber ?? '',
        ];

        // Create bill in ToyyibPay
        $result = $this->toyyibpay->createBill($billData);

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
    }

    /**
     * Payment callback from ToyyibPay
     */
    public function paymentCallback(Request $request)
    {
        Log::info('ToyyibPay Callback', ['data' => $request->all()]);

        $billCode = $request->input('billcode');
        $statusId = $request->input('status_id');
        $transactionId = $request->input('transaction_id');
        $orderId = $request->input('order_id');

        // Find payment by bill code
        $payment = Payment::where('BillCode', $billCode)->first();

        if (!$payment) {
            Log::error('Payment not found for bill code: ' . $billCode);
            return redirect()->route('user.HomePage')
                ->with('error', 'Payment record not found');
        }

        $booking = Booking::with(['item', 'user'])->findOrFail($payment->BookingID);

        // Status: 1 = Successful, 2 = Pending, 3 = Failed
        if ($statusId == 1) {
            // Payment successful
            DB::beginTransaction();
            try {
                $payment->update([
                    'Status' => 'successful',
                    'TransactionID' => $transactionId,
                    'PaymentMethod' => 'ToyyibPay - FPX',
                    'PaymentDate' => now(),
                    'PaymentResponse' => json_encode($request->all())
                ]);

                // Update booking TotalPaid with the payment amount (deposit + service fee)
                $booking->update([
                    'TotalPaid' => $payment->Amount
                ]);

                // Keep booking as pending - owner needs to approve
                // Note: Status remains 'pending' until owner approves the booking
                // No status update here

                // Update item availability automatically
                $booking->item->updateAvailabilityStatus();

                // Calculate rental amount to remind user
                $rentalAmount = $booking->TotalAmount;

                // Create notification for user (renter) - payment done, waiting for approval
                Notification::create([
                    'UserID' => $booking->UserID,
                    'Type' => 'payment',
                    'Title' => '✅ Deposit Payment Successful',
                    'Content' => 'Your deposit payment of RM ' . number_format($payment->Amount, 2) . ' for booking #' . $booking->BookingID . ' has been received. Waiting for the owner to approve your booking request. Rental fee of RM ' . number_format($rentalAmount, 2) . ' to be paid directly to owner upon approval.',
                    'RelatedID' => $payment->PaymentID,
                    'RelatedType' => 'payment',
                    'CreatedAt' => now()
                ]);

                // Notify item owner - payment received, needs to approve
                Notification::create([
                    'UserID' => $booking->item->UserID,
                    'Type' => 'booking',
                    'Title' => '💰 Booking Payment Received - Action Required',
                    'Content' => 'Booking #' . $booking->BookingID . ' for ' . $booking->item->ItemName . ' - Deposit has been paid by ' . $booking->user->UserName . '. Please review and approve or cancel this booking request.',
                    'RelatedID' => $booking->BookingID,
                    'RelatedType' => 'booking',
                    'CreatedAt' => now()
                ]);

                DB::commit();

                Log::info('Payment successful for booking: ' . $booking->BookingID);

                return redirect()->route('booking.show', $booking->BookingID)
                    ->with('success', 'Deposit payment successful! Your booking request is pending owner approval. You will be notified once the owner reviews your request.');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Payment callback error: ' . $e->getMessage());
                
                return redirect()->route('booking.show', $booking->BookingID)
                    ->with('error', 'Payment processing error. Please contact support.');
            }

        } elseif ($statusId == 3) {
            // Payment failed
            $payment->update([
                'Status' => 'failed',
                'PaymentResponse' => json_encode($request->all())
            ]);

            Log::warning('Payment failed for booking: ' . $booking->BookingID);

            return redirect()->route('booking.show', $booking->BookingID)
                ->with('error', 'Payment failed. Please try again or contact support.');

        } else {
            // Payment pending
            $payment->update([
                'Status' => 'pending',
                'PaymentResponse' => json_encode($request->all())
            ]);

            Log::info('Payment pending for booking: ' . $booking->BookingID);

            return redirect()->route('booking.show', $booking->BookingID)
                ->with('info', 'Payment is pending. Please wait for confirmation.');
        }
    }

    /**
     * Show payment details
     */
    public function show($paymentId)
    {
        $payment = Payment::with(['booking.item', 'booking.user'])
            ->findOrFail($paymentId);

        // Check authorization
        if ($payment->booking->UserID !== auth()->id() && $payment->booking->item->UserID !== auth()->id()) {
            abort(403, 'Unauthorized access to this payment');
        }

        return view('payments.show', compact('payment'));
    }

    /**
     * Check payment status
     */
    public function checkStatus($bookingId)
    {
        $booking = Booking::with('item')->findOrFail($bookingId);

        // Check authorization
        if ($booking->UserID !== auth()->id() && $booking->item->UserID !== auth()->id()) {
            abort(403);
        }

        $payment = Payment::where('BookingID', $bookingId)
            ->latest()
            ->first();

        if (!$payment) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'No payment found for this booking'
            ]);
        }

        return response()->json([
            'status' => $payment->Status,
            'amount' => $payment->Amount,
            'transaction_id' => $payment->TransactionID,
            'payment_date' => $payment->PaymentDate ? $payment->PaymentDate->format('Y-m-d H:i:s') : null
        ]);
    }

    /**
     * Refund payment (for deposit refund)
     */
    public function refund($paymentId)
    {
        $payment = Payment::with(['booking.item', 'booking.deposit'])->findOrFail($paymentId);

        // Only item owner can initiate refund
        if ($payment->booking->item->UserID !== auth()->id()) {
            abort(403, 'Unauthorized action');
        }

        // Check if payment is successful and booking is completed
        if ($payment->Status !== 'successful') {
            return back()->with('error', 'Can only refund successful payments');
        }

        if ($payment->booking->Status !== 'completed') {
            return back()->with('error', 'Booking must be completed before refunding deposit');
        }

        DB::beginTransaction();
        try {
            // Update payment status to refunded
            $payment->update([
                'Status' => 'refunded'
            ]);

            // Update deposit status
            if ($payment->booking->deposit) {
                $payment->booking->deposit->update([
                    'Status' => 'refunded',
                    'DateRefunded' => now(),
                    'RefundMethod' => 'Manual Refund',
                    'RefundReference' => 'REFUND-' . $payment->PaymentID . '-' . time()
                ]);
            }

            // Notify renter
            Notification::create([
                'UserID' => $payment->booking->UserID,
                'Type' => 'payment',
                'Title' => '💰 Deposit Refund Processed',
                'Content' => 'Your deposit of RM ' . number_format($payment->booking->DepositAmount, 2) . ' for booking #' . $payment->booking->BookingID . ' has been refunded.',
                'RelatedID' => $payment->PaymentID,
                'RelatedType' => 'payment',
                'CreatedAt' => now()
            ]);

            DB::commit();

            Log::info('Deposit refunded for payment: ' . $payment->PaymentID);

            return back()->with('success', 'Deposit refund processed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Refund error: ' . $e->getMessage());
            return back()->with('error', 'Failed to process refund. Please try again.');
        }
    }

    /**
     * Test payment page (simulates ToyyibPay during verification)
     */
    public function testPayment($billCode)
    {
        $payment = Payment::where('BillCode', $billCode)->first();

        if (!$payment) {
            return redirect()->route('user.HomePage')
                ->with('error', 'Payment not found');
        }

        $booking = Booking::with(['item', 'user'])->findOrFail($payment->BookingID);

        return view('payments.test', compact('payment', 'booking', 'billCode'));
    }

    /**
     * Get payment history for a booking
     */
    public function history($bookingId)
    {
        $booking = Booking::with(['item', 'user'])->findOrFail($bookingId);

        // Check authorization
        if ($booking->UserID !== auth()->id() && $booking->item->UserID !== auth()->id()) {
            abort(403);
        }

        $payments = Payment::where('BookingID', $bookingId)
            ->orderBy('CreatedAt', 'desc')
            ->get();

        return view('payments.history', compact('booking', 'payments'));
    }
}