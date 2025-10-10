<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Penalty;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['reporter', 'reportedUser', 'booking', 'item', 'penalty']);

        // Search
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('Subject', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('Description', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhereHas('reporter', function($q) use ($searchTerm) {
                      $q->where('UserName', 'LIKE', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('reportedUser', function($q) use ($searchTerm) {
                      $q->where('UserName', 'LIKE', '%' . $searchTerm . '%');
                  });
            });
        }

        // Filters
        if ($request->has('type') && $request->type != 'all') {
            $query->where('ReportType', $request->type);
        }

        if ($request->has('status') && $request->status != 'all') {
            $query->where('Status', $request->status);
        }

        if ($request->has('priority') && $request->priority != 'all') {
            $query->where('Priority', $request->priority);
        }

        $query->orderBy('DateReported', 'desc');
        $reports = $query->paginate(15);

        // Stats
        $totalReports = Report::count();
        $pendingReports = Report::where('Status', 'pending')->count();
        $resolvedReports = Report::where('Status', 'resolved')->count();
        $dismissedReports = Report::where('Status', 'dismissed')->count();

        return view('admin.reports', compact(
            'reports',
            'totalReports',
            'pendingReports',
            'resolvedReports',
            'dismissedReports'
        ));
    }

    public function show($id)
    {
        $report = Report::with([
            'reporter',
            'reportedUser',
            'booking.item',
            'item',
            'penalty'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'report' => [
                'id' => $report->ReportID,
                'type' => ucwords(str_replace('-', ' ', $report->ReportType)),
                'priority' => ucfirst($report->Priority),
                'subject' => $report->Subject,
                'description' => $report->Description,
                'status' => ucfirst($report->Status),
                'date_reported' => $report->DateReported->format('M d, Y'),
                'date_resolved' => $report->DateResolved ? $report->DateResolved->format('M d, Y') : 'Not resolved',
                'evidence' => $report->EvidencePath,
                'admin_notes' => $report->AdminNotes ?? 'No notes',
                'reporter' => [
                    'name' => $report->reporter->UserName,
                    'email' => $report->reporter->Email,
                ],
                'reported_user' => [
                    'name' => $report->reportedUser->UserName,
                    'email' => $report->reportedUser->Email,
                ],
                'booking' => $report->booking ? [
                    'id' => $report->booking->BookingID,
                    'item' => $report->booking->item->ItemName,
                    'dates' => $report->booking->StartDate->format('M d') . ' - ' . $report->booking->EndDate->format('M d, Y'),
                ] : null,
                'penalty' => $report->penalty ? [
                    'amount' => 'RM ' . number_format($report->penalty->PenaltyAmount, 2),
                    'resolved' => $report->penalty->ResolvedStatus ? 'Yes' : 'No',
                ] : null,
            ]
        ]);
    }

    public function resolve(Request $request, $id)
    {
        $validated = $request->validate([
            'apply_penalty' => 'required|boolean',
            'penalty_amount' => 'required_if:apply_penalty,true|numeric|min:0',
            'penalty_description' => 'required_if:apply_penalty,true|string',
            'admin_notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $report = Report::findOrFail($id);
            
            // Update report
            $report->Status = 'resolved';
            $report->ReviewedByAdminID = auth()->id();
            $report->AdminNotes = $request->admin_notes;
            $report->DateResolved = Carbon::now();
            $report->save();

            // Apply penalty if requested
            if ($request->apply_penalty) {
                Penalty::create([
                    'ReportID' => $report->ReportID,
                    'ReportedByID' => $report->ReportedByID,
                    'ReportedUserID' => $report->ReportedUserID,
                    'BookingID' => $report->BookingID,
                    'ItemID' => $report->ItemID,
                    'ApprovedByAdminID' => auth()->id(),
                    'Description' => $request->penalty_description,
                    'PenaltyAmount' => $request->penalty_amount,
                    'ResolvedStatus' => false,
                    'DateReported' => Carbon::now(),
                ]);
            }

            DB::commit();
            return back()->with('success', 'Report resolved successfully' . ($request->apply_penalty ? ' with penalty applied' : ''));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed: ' . $e->getMessage());
        }
    }

    public function dismiss(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $report = Report::findOrFail($id);
        $report->Status = 'dismissed';
        $report->ReviewedByAdminID = auth()->id();
        $report->AdminNotes = $request->admin_notes;
        $report->DateResolved = Carbon::now();
        $report->save();

        return back()->with('success', 'Report dismissed');
    }

    public function export()
    {
        $reports = Report::with(['reporter', 'reportedUser', 'penalty'])->get();

        $filename = 'reports_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($reports) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Report ID', 'Type', 'Priority', 'Reporter', 'Reported User', 'Subject', 'Status', 'Date', 'Penalty Amount']);
            
            foreach ($reports as $report) {
                fputcsv($file, [
                    '#R' . str_pad($report->ReportID, 3, '0', STR_PAD_LEFT),
                    ucwords(str_replace('-', ' ', $report->ReportType)),
                    ucfirst($report->Priority),
                    $report->reporter->UserName,
                    $report->reportedUser->UserName,
                    $report->Subject,
                    ucfirst($report->Status),
                    $report->DateReported->format('Y-m-d'),
                    $report->penalty ? 'RM ' . number_format($report->penalty->PenaltyAmount, 2) : 'N/A',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}