<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penalty;

class PenaltiesController extends Controller
{
    public function index(Request $request)
    {
        $query = Penalty::with(['reportedUser', 'report', 'booking', 'item']);

        // Search
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('Description', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhereHas('reportedUser', function($q) use ($searchTerm) {
                      $q->where('UserName', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('Email', 'LIKE', '%' . $searchTerm . '%');
                  });
            });
        }

        // Status filter
        if ($request->has('status') && $request->status != 'all') {
            if ($request->status == 'pending') {
                $query->where('ResolvedStatus', 0);
            } else if ($request->status == 'resolved') {
                $query->where('ResolvedStatus', 1);
            }
        }

        // Sort
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('DateReported', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('DateReported', 'asc');
                    break;
                case 'amount-high':
                    $query->orderBy('PenaltyAmount', 'desc');
                    break;
                case 'amount-low':
                    $query->orderBy('PenaltyAmount', 'asc');
                    break;
                default:
                    $query->orderBy('DateReported', 'desc');
            }
        } else {
            $query->orderBy('DateReported', 'desc');
        }

        $penalties = $query->paginate(15);

        // Calculate stats
        $totalPenalties = Penalty::count();
        $pendingPenalties = Penalty::where('ResolvedStatus', 0)->count();
        $resolvedPenalties = Penalty::where('ResolvedStatus', 1)->count();
        $totalAmount = Penalty::sum('PenaltyAmount') ?? 0;

        // IMPORTANT: Return with all variables using compact()
        return view('admin.penalties', compact(
            'penalties',
            'totalPenalties',
            'pendingPenalties',
            'resolvedPenalties',
            'totalAmount'
        ));
    }

    public function show($id)
    {
        $penalty = Penalty::with([
            'reportedUser',
            'report',
            'booking.item',
            'item',
            'approvedByAdmin'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'penalty' => [
                'id' => $penalty->PenaltyID,
                'amount' => number_format($penalty->PenaltyAmount, 2),
                'description' => $penalty->Description,
                'date_issued' => $penalty->DateReported->format('M d, Y'),
                'status' => $penalty->ResolvedStatus ? 'Resolved' : 'Pending',
                'user' => [
                    'name' => $penalty->reportedUser->UserName,
                    'email' => $penalty->reportedUser->Email,
                ],
                'report' => $penalty->report ? [
                    'id' => $penalty->report->ReportID,
                    'type' => ucwords(str_replace('-', ' ', $penalty->report->ReportType)),
                ] : null,
                'booking' => $penalty->booking ? [
                    'item' => $penalty->booking->item->ItemName,
                    'dates' => $penalty->booking->StartDate->format('M d') . ' - ' . $penalty->booking->EndDate->format('M d, Y'),
                ] : null,
                'item' => $penalty->item ? [
                    'name' => $penalty->item->ItemName,
                ] : null,
                'approved_by' => $penalty->approvedByAdmin ? $penalty->approvedByAdmin->UserName : null,
            ]
        ]);
    }

    public function resolve($id)
    {
        $penalty = Penalty::findOrFail($id);
        $penalty->ResolvedStatus = true;
        $penalty->save();

        return back()->with('success', 'Penalty marked as resolved');
    }

    public function export()
    {
        $penalties = Penalty::with(['reportedUser', 'report'])->get();

        $filename = 'penalties_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($penalties) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Penalty ID', 'User', 'Email', 'Description', 'Amount', 'Date', 'Status', 'Report ID']);
            
            foreach ($penalties as $penalty) {
                fputcsv($file, [
                    '#P' . str_pad($penalty->PenaltyID, 3, '0', STR_PAD_LEFT),
                    $penalty->reportedUser->UserName,
                    $penalty->reportedUser->Email,
                    $penalty->Description,
                    'RM ' . number_format($penalty->PenaltyAmount, 2),
                    $penalty->DateReported->format('Y-m-d'),
                    $penalty->ResolvedStatus ? 'Resolved' : 'Pending',
                    $penalty->report ? '#R' . str_pad($penalty->report->ReportID, 3, '0', STR_PAD_LEFT) : 'N/A',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}