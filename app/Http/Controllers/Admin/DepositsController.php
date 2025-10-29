<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\Booking;
use App\Models\RefundQueue;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DepositsController extends Controller
{
    /**
     * Display all deposits with filters
     */
    public function index(Request $request)
    {
        // Start query with relationships
        $query = Deposit::with([
            'booking.user',
            'booking.item.user'
        ]);

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('booking.user', function($q) use ($searchTerm) {
                    $q->where('UserName', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('Email', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orWhereHas('booking.item', function($q) use ($searchTerm) {
                    $q->where('ItemName', 'LIKE', '%' . $searchTerm . '%');
                });
            });
        }

        // Status filter
        if ($request->has('status') && $request->status != 'all') {
            $query->where('Status', $request->status);
        }

        // Amount filter
        if ($request->has('amount') && $request->amount != 'all') {
            switch ($request->amount) {
                case '0-500':
                    $query->whereBetween('DepositAmount', [0, 500]);
                    break;
                case '500-1000':
                    $query->whereBetween('DepositAmount', [500, 1000]);
                    break;
                case '1000+':
                    $query->where('DepositAmount', '>', 1000);
                    break;
            }
        }

        // Sort filter
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('DateCollected', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('DateCollected', 'asc');
                    break;
                case 'amount-high':
                    $query->orderBy('DepositAmount', 'desc');
                    break;
                case 'amount-low':
                    $query->orderBy('DepositAmount', 'asc');
                    break;
                default:
                    $query->orderBy('DateCollected', 'desc');
            }
        } else {
            $query->orderBy('DateCollected', 'desc');
        }

        // Paginate results
        $deposits = $query->paginate(10);

        // Calculate statistics
        $totalDeposits = Deposit::sum('DepositAmount') ?? 0;
        $refundedAmount = Deposit::where('Status', 'refunded')->sum('DepositAmount') ?? 0;
        $heldAmount = Deposit::where('Status', 'held')->sum('DepositAmount') ?? 0;
        $forfeitedAmount = Deposit::where('Status', 'forfeited')->sum('DepositAmount') ?? 0;
        $totalCount = Deposit::count();

        return view('admin.deposits', compact(
            'deposits',
            'totalDeposits',
            'refundedAmount',
            'heldAmount',
            'forfeitedAmount',
            'totalCount'
        ));
    }

    /**
     * View deposit details
     */
    public function show($id)
    {
        $deposit = Deposit::with([
            'booking.user',
            'booking.item.user'
        ])->findOrFail($id);

        // Check if there's a refund queue entry
        $refundQueue = RefundQueue::where('DepositID', $id)->first();

        return response()->json([
            'success' => true,
            'deposit' => [
                'id' => $deposit->DepositID,
                'amount' => number_format($deposit->DepositAmount, 2),
                'status' => ucfirst($deposit->Status),
                'date_collected' => $deposit->DateCollected->format('M d, Y'),
                'refund_date' => $deposit->RefundDate ? $deposit->RefundDate->format('M d, Y') : 'N/A',
                'notes' => $deposit->Notes ?? 'No notes',
                'user' => [
                    'name' => $deposit->booking->user->UserName,
                    'email' => $deposit->booking->user->Email,
                    'avatar' => $deposit->booking->user->ProfileImage ? asset('storage/' . $deposit->booking->user->ProfileImage) : null,
                    'bank_name' => $deposit->booking->user->BankName ?? 'Not provided',
                    'bank_account_number' => $deposit->booking->user->BankAccountNumber ?? 'Not provided',
                    'bank_account_holder' => $deposit->booking->user->BankAccountHolderName ?? 'Not provided',
                ],
                'item' => [
                    'name' => $deposit->booking->item->ItemName,
                    'owner' => $deposit->booking->item->user->UserName,
                ],
                'booking' => [
                    'start_date' => $deposit->booking->StartDate->format('M d, Y'),
                    'end_date' => $deposit->booking->EndDate->format('M d, Y'),
                    'duration' => $deposit->booking->StartDate->diffInDays($deposit->booking->EndDate) . ' days',
                    'status' => ucfirst($deposit->booking->Status),
                ],
                'refund_queue' => $refundQueue ? [
                    'status' => ucfirst($refundQueue->Status),
                    'reference' => $refundQueue->RefundReference,
                    'processed_at' => $refundQueue->ProcessedAt ? $refundQueue->ProcessedAt->format('M d, Y g:i A') : null,
                ] : null,
            ]
        ]);
    }

    /**
     * Process refund for a deposit - Creates refund queue entry
     */
    public function refund(Request $request, $id)
    {
        try {
            $deposit = Deposit::with('booking.user')->findOrFail($id);

            // Check if can be refunded
            if ($deposit->Status !== 'held') {
                return back()->with('error', 'This deposit cannot be refunded (Status: ' . $deposit->Status . ')');
            }

            // Check if user has bank details
            $user = $deposit->booking->user;
            if (!$user->BankName || !$user->BankAccountNumber || !$user->BankAccountHolderName) {
                return back()->with('error', 'User has not provided bank account details. Cannot process refund.');
            }

            // Check if refund queue entry already exists
            $existingQueue = RefundQueue::where('DepositID', $id)->first();
            if ($existingQueue) {
                return back()->with('error', 'Refund request already exists for this deposit.');
            }

            DB::beginTransaction();

            // Create refund queue entry
            $refundQueue = RefundQueue::create([
                'DepositID' => $deposit->DepositID,
                'BookingID' => $deposit->BookingID,
                'UserID' => $user->UserID,
                'RefundAmount' => $deposit->DepositAmount,
                'Status' => 'pending',
                'BankName' => $user->BankName,
                'BankAccountNumber' => $user->BankAccountNumber,
                'BankAccountHolderName' => $user->BankAccountHolderName,
                'Notes' => $request->input('notes', 'Refund initiated by admin'),
            ]);

            // Update deposit status to processing
            $deposit->Status = 'processing_refund';
            $deposit->Notes = 'Refund queued - Ref: #RQ' . str_pad($refundQueue->RefundQueueID, 4, '0', STR_PAD_LEFT);
            $deposit->save();

            DB::commit();

            return redirect()->route('admin.refund-queue')->with('success', 'Refund request created successfully. Please process the bank transfer.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create refund request: ' . $e->getMessage());
        }
    }

    /**
     * Forfeit a deposit
     */
    public function forfeit(Request $request, $id)
    {
        try {
            $deposit = Deposit::findOrFail($id);
            
            if ($deposit->Status !== 'held') {
                return back()->with('error', 'Can only forfeit held deposits');
            }
            
            DB::beginTransaction();
            
            $deposit->Status = 'forfeited';
            $deposit->Notes = $request->input('reason', 'Forfeited by admin');
            $deposit->save();
            
            DB::commit();
            
            return back()->with('success', 'Deposit forfeited successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to forfeit deposit: ' . $e->getMessage());
        }
    }

    /**
     * Export deposits data to CSV
     */
    public function export()
    {
        $deposits = Deposit::with([
            'booking.user',
            'booking.item.user'
        ])->get();

        $filename = 'deposits_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($deposits) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'Transaction ID',
                'User',
                'Email',
                'Item',
                'Owner',
                'Deposit Amount',
                'Booking Start',
                'Booking End',
                'Duration (Days)',
                'Date Collected',
                'Refund Date',
                'Status',
                'Notes'
            ]);
            
            // Add data
            foreach ($deposits as $deposit) {
                $duration = $deposit->booking->StartDate->diffInDays($deposit->booking->EndDate);
                
                fputcsv($file, [
                    '#D' . str_pad($deposit->DepositID, 3, '0', STR_PAD_LEFT),
                    $deposit->booking->user->UserName ?? 'N/A',
                    $deposit->booking->user->Email ?? 'N/A',
                    $deposit->booking->item->ItemName ?? 'N/A',
                    $deposit->booking->item->user->UserName ?? 'N/A',
                    'RM ' . number_format($deposit->DepositAmount, 2),
                    $deposit->booking->StartDate->format('Y-m-d'),
                    $deposit->booking->EndDate->format('Y-m-d'),
                    $duration,
                    $deposit->DateCollected->format('Y-m-d'),
                    $deposit->RefundDate ? $deposit->RefundDate->format('Y-m-d') : 'N/A',
                    ucfirst($deposit->Status),
                    $deposit->Notes ?? 'N/A',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate financial report
     */
    public function generateReport()
    {
        $currentYear = date('Y');
        
        $stats = [
            'total_deposits' => Deposit::sum('DepositAmount') ?? 0,
            'refunded' => Deposit::where('Status', 'refunded')->sum('DepositAmount') ?? 0,
            'held' => Deposit::where('Status', 'held')->sum('DepositAmount') ?? 0,
            'forfeited' => Deposit::where('Status', 'forfeited')->sum('DepositAmount') ?? 0,
            'partial' => Deposit::where('Status', 'partial')->sum('DepositAmount') ?? 0,
            'total_transactions' => Deposit::count(),
            'monthly_breakdown' => Deposit::select(
                DB::raw('MONTH(DateCollected) as month'),
                DB::raw('YEAR(DateCollected) as year'),
                DB::raw('SUM(DepositAmount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('DateCollected', $currentYear)
            ->groupBy(DB::raw('YEAR(DateCollected)'), DB::raw('MONTH(DateCollected)'))
            ->orderBy(DB::raw('MONTH(DateCollected)'))
            ->get()
            ->map(function($item) {
                $item->month_name = Carbon::create()->month($item->month)->format('F');
                return $item;
            }),
        ];

        return response()->json([
            'success' => true,
            'report' => $stats
        ]);
    }
}