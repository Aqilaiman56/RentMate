<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RefundQueue;
use App\Models\Deposit;
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
            'refund_reference' => 'required|string|max:100',
            'proof_of_transfer' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        try {
            DB::beginTransaction();

            $refund = RefundQueue::with('deposit')->findOrFail($id);

            if (!in_array($refund->Status, ['pending', 'processing'])) {
                return back()->with('error', 'Can only complete pending or processing refunds');
            }

            // Upload proof of transfer if provided
            $proofPath = null;
            if ($request->hasFile('proof_of_transfer')) {
                $proofPath = $request->file('proof_of_transfer')->store('refund_proofs', 'public');
            }

            // Update refund queue
            $refund->Status = 'completed';
            $refund->RefundReference = $request->refund_reference;
            $refund->ProofOfTransfer = $proofPath;
            $refund->ProcessedAt = Carbon::now();
            $refund->ProcessedBy = auth()->id();
            $refund->save();

            // Update deposit status
            $deposit = $refund->deposit;
            $deposit->Status = 'refunded';
            $deposit->RefundDate = Carbon::now();
            $deposit->Notes = 'Refunded via bank transfer - Ref: ' . $request->refund_reference;
            $deposit->save();

            DB::commit();

            return back()->with('success', 'Refund completed successfully!');

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

            $refund = RefundQueue::with('deposit')->findOrFail($id);

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

            DB::commit();

            return back()->with('success', 'Refund marked as failed');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }
}
