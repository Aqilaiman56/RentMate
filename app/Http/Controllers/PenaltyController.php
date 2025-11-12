<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penalty;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class PenaltyController extends Controller
{
    /**
     * Display all penalties (Admin)
     */
    public function index(Request $request): View
    {
        $query = Penalty::with(['reportedBy', 'reportedUser', 'booking', 'item', 'approvedByAdmin']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'pending') {
                $query->where('ResolvedStatus', false);
            } elseif ($request->status === 'resolved') {
                $query->where('ResolvedStatus', true);
            }
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Description', 'like', "%{$search}%")
                  ->orWhereHas('reportedUser', function($q2) use ($search) {
                      $q2->where('UserName', 'like', "%{$search}%");
                  });
            });
        }

        $penalties = $query->orderBy('DateReported', 'desc')->paginate(20);

        // Stats
        $totalPenalties = Penalty::count();
        $pendingPenalties = Penalty::where('ResolvedStatus', false)->count();
        $resolvedPenalties = Penalty::where('ResolvedStatus', true)->count();
        $totalAmount = Penalty::where('ResolvedStatus', false)->sum('PenaltyAmount');

        return view('admin.penalties', compact('penalties', 'totalPenalties', 'pendingPenalties', 'resolvedPenalties', 'totalAmount'));
    }

    /**
     * Show user's penalty history
     */
    public function userHistory(): View
    {
        $userId = Auth::id();

        $penalties = Penalty::with(['reportedBy', 'booking', 'item', 'approvedByAdmin', 'report'])
            ->where('ReportedUserID', $userId)
            ->orderBy('DateReported', 'desc')
            ->get();

        $totalPenalties = $penalties->count();
        $pendingPenalties = $penalties->where('ResolvedStatus', false)->count();
        $totalAmount = $penalties->where('ResolvedStatus', false)->sum('PenaltyAmount');

        return view('user.penalty-history', compact('penalties', 'totalPenalties', 'pendingPenalties', 'totalAmount'));
    }

    /**
     * Create penalty from report (Admin)
     */
    public function createFromReport(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ReportID' => 'required|exists:reports,ReportID',
            'PenaltyAmount' => 'required|numeric|min:0|max:999999.99',
            'Description' => 'required|string|max:2000',
        ]);

        $report = Report::findOrFail($validated['ReportID']);

        // Check if penalty already exists for this report
        if ($report->penalty()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'A penalty has already been issued for this report.'
            ], 400);
        }

        // Create penalty
        $penalty = Penalty::create([
            'ReportID' => $report->ReportID,
            'ReportedByID' => $report->ReportedByID,
            'ReportedUserID' => $report->ReportedUserID,
            'BookingID' => $report->BookingID,
            'ItemID' => $report->ItemID,
            'ApprovedByAdminID' => Auth::id(),
            'Description' => $validated['Description'],
            'EvidencePath' => $report->EvidencePath,
            'PenaltyAmount' => $validated['PenaltyAmount'],
            'ResolvedStatus' => false,
            'DateReported' => now(),
        ]);

        // Update report status
        $report->update([
            'Status' => 'resolved',
            'ReviewedByAdminID' => Auth::id(),
            'AdminNotes' => 'Penalty issued: RM ' . number_format($validated['PenaltyAmount'], 2),
            'DateResolved' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Penalty issued successfully.',
            'penalty' => $penalty
        ]);
    }

    /**
     * Show penalty details
     */
    public function show($id): JsonResponse
    {
        $penalty = Penalty::with(['reportedBy', 'reportedUser', 'booking', 'item', 'approvedByAdmin', 'report'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'penalty' => $penalty
        ]);
    }

    /**
     * Mark penalty as resolved/paid (Admin)
     */
    public function resolve(Request $request, $id): RedirectResponse
    {
        $penalty = Penalty::findOrFail($id);

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $penalty->update([
            'ResolvedStatus' => true
        ]);

        // Update report if linked
        if ($penalty->report) {
            $penalty->report->update([
                'AdminNotes' => ($penalty->report->AdminNotes ?? '') . "\nPenalty marked as paid/resolved: " . ($validated['admin_notes'] ?? ''),
            ]);
        }

        return redirect()->back()->with('success', 'Penalty marked as resolved.');
    }

    /**
     * Delete penalty (Admin)
     */
    public function destroy($id): RedirectResponse
    {
        $penalty = Penalty::findOrFail($id);
        $penalty->delete();

        return redirect()->back()->with('success', 'Penalty deleted successfully.');
    }

    /**
     * Export penalties to CSV (Admin)
     */
    public function export(Request $request)
    {
        $penalties = Penalty::with(['reportedBy', 'reportedUser', 'approvedByAdmin'])
            ->orderBy('DateReported', 'desc')
            ->get();

        $filename = 'penalties_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($penalties) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'Penalty ID',
                'Reported By',
                'Reported User',
                'Description',
                'Penalty Amount',
                'Status',
                'Date Reported',
                'Approved By Admin'
            ]);

            // Data
            foreach ($penalties as $penalty) {
                fputcsv($file, [
                    $penalty->PenaltyID,
                    $penalty->reportedBy->UserName ?? 'N/A',
                    $penalty->reportedUser->UserName ?? 'N/A',
                    $penalty->Description,
                    'RM ' . number_format($penalty->PenaltyAmount, 2),
                    $penalty->ResolvedStatus ? 'Resolved' : 'Pending',
                    $penalty->DateReported->format('Y-m-d H:i:s'),
                    $penalty->approvedByAdmin->UserName ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
