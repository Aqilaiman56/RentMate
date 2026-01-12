<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RefundQueue;
use App\Models\Deposit;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RefundQueueController extends Controller
{
    /**
     * Display the refund queue
     */
    public function index(Request $request)
    {
        $query = RefundQueue::with([
            'user',
            'deposit.booking.item',
            'processor'
        ]);

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('Status', $request->status);
        }

        // Search by user name or reference
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('user', function($q) use ($searchTerm) {
                    $q->where('UserName', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('Email', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orWhere('RefundReference', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('BankAccountNumber', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Sort
        $query->orderBy('created_at', 'desc');

        $refunds = $query->paginate(15);

        // Statistics
        $stats = [
            'pending' => RefundQueue::where('Status', 'pending')->count(),
            'processing' => RefundQueue::where('Status', 'processing')->count(),
            'completed' => RefundQueue::where('Status', 'completed')->count(),
            'failed' => RefundQueue::where('Status', 'failed')->count(),
            'pending_amount' => RefundQueue::where('Status', 'pending')->sum('RefundAmount'),
            'total_refunded' => RefundQueue::where('Status', 'completed')->sum('RefundAmount'),
        ];

        return view('admin.refund-queue', compact('refunds', 'stats'));
    }

    /**
     * Mark refund as processing
     */
    public function markProcessing($id)
    {
        try {
            $refund = RefundQueue::findOrFail($id);

            if ($refund->Status !== 'pending') {
                return back()->with('error', 'Can only process pending refunds');
            }

            $refund->Status = 'processing';
            $refund->ProcessedBy = auth()->id();
            $refund->save();

            return back()->with('success', 'Refund marked as processing');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    /**
     * Complete refund with proof of transfer
     */
    public function complete(Request $request, $id)
    {
        $request->validate([
            'proof_of_transfer' => 'required|image|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        try {
            DB::beginTransaction();

            $refund = RefundQueue::with('deposit')->findOrFail($id);

            if (!in_array($refund->Status, ['pending', 'processing'])) {
                return back()->with('error', 'Can only complete pending or processing refunds');
            }

            // Upload proof of transfer
            $proofPath = $request->file('proof_of_transfer')->store('refund_proofs', 'public');

            // Generate automatic refund reference
            $refundReference = RefundQueue::generateRefundReference($refund->RefundQueueID);

            // Update refund queue
            $refund->Status = 'completed';
            $refund->RefundReference = $refundReference;
            $refund->RefundMethod = 'manual';
            $refund->ProofOfTransfer = $proofPath;
            $refund->ProcessedAt = Carbon::now();
            $refund->ProcessedBy = auth()->id();
            $refund->save();

            // Update deposit status
            $deposit = $refund->deposit;
            $deposit->Status = 'refunded';
            $deposit->RefundDate = Carbon::now();
            $deposit->RefundReference = $refundReference;
            $deposit->Notes = 'Refunded via bank transfer - Ref: ' . $refundReference;
            $deposit->save();

            // Send notification to user
            Notification::create([
                'UserID' => $refund->UserID,
                'Type' => 'payment',
                'Title' => '💰 Deposit Refund Processed',
                'Content' => 'Your deposit of RM ' . number_format($refund->RefundAmount, 2) . ' has been refunded to your bank account. Reference: ' . $refundReference . '. Please allow 1-3 business days for the transfer to complete.',
                'RelatedID' => $refund->BookingID,
                'RelatedType' => 'Booking',
                'IsRead' => false,
                'CreatedAt' => Carbon::now()
            ]);

            DB::commit();

            return back()->with('success', 'Refund completed successfully! Reference: ' . $refundReference);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to complete refund: ' . $e->getMessage());
        }
    }


    /**
     * Mark refund as failed
     */
    public function markFailed(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $refund = RefundQueue::with(['deposit', 'user'])->findOrFail($id);

            $refund->Status = 'failed';
            $refund->Notes = $request->reason;
            $refund->ProcessedAt = Carbon::now();
            $refund->ProcessedBy = auth()->id();
            $refund->save();

            // Revert deposit status back to held
            $deposit = $refund->deposit;
            $deposit->Status = 'held';
            $deposit->Notes = 'Refund failed: ' . $request->reason;
            $deposit->save();

            // Send notification to user about failed refund
            Notification::create([
                'UserID' => $refund->UserID,
                'Type' => 'refund_failed',
                'Title' => 'Refund Request Failed',
                'Content' => 'Your refund request of RM ' . number_format($refund->RefundAmount, 2) . ' has failed. Reason: ' . $request->reason . '. Please contact the administrator to resolve this issue and process your refund manually.',
                'RelatedID' => $refund->RefundQueueID,
                'RelatedType' => 'RefundQueue',
                'IsRead' => false,
                'CreatedAt' => Carbon::now()
            ]);

            DB::commit();

            return back()->with('success', 'Refund marked as failed and user has been notified');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }
}
