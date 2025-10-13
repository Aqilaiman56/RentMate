<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaxesController extends Controller
{
    public function index(Request $request)
    {
        // Get year filter or default to current year
        $year = $request->input('year', date('Y'));
        
        // Get monthly breakdown
        $monthlyTaxes = Tax::select(
                DB::raw('MONTH(DateCollected) as month'),
                DB::raw('YEAR(DateCollected) as year'),
                DB::raw('SUM(TaxAmount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('DateCollected', $year)
            ->groupBy(DB::raw('YEAR(DateCollected)'), DB::raw('MONTH(DateCollected)'))
            ->orderBy(DB::raw('MONTH(DateCollected)'))
            ->get()
            ->map(function($item) {
                $item->month_name = Carbon::create()->month($item->month)->format('F');
                return $item;
            });

        // Calculate statistics
        $totalTaxes = Tax::whereYear('DateCollected', $year)->sum('TaxAmount');
        $totalTransactions = Tax::whereYear('DateCollected', $year)->count();
        $averagePerMonth = $monthlyTaxes->count() > 0 ? $totalTaxes / $monthlyTaxes->count() : 0;
        
        // Get all available years for filter
        $availableYears = Tax::select(DB::raw('YEAR(DateCollected) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Get recent transactions
        $recentTaxes = Tax::with(['booking.item', 'user'])
            ->whereYear('DateCollected', $year)
            ->orderBy('DateCollected', 'desc')
            ->limit(20)
            ->get();

        return view('admin.taxes', compact(
            'monthlyTaxes',
            'totalTaxes',
            'totalTransactions',
            'averagePerMonth',
            'year',
            'availableYears',
            'recentTaxes'
        ));
    }

    public function export(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $taxes = Tax::with(['booking.item', 'user'])
            ->whereYear('DateCollected', $year)
            ->orderBy('DateCollected', 'desc')
            ->get();

        $filename = 'taxes_export_' . $year . '_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($taxes) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Tax ID', 'User', 'Booking ID', 'Item', 'Tax Amount', 'Date', 'Month', 'Year']);
            
            foreach ($taxes as $tax) {
                fputcsv($file, [
                    '#T' . str_pad($tax->TaxID, 4, '0', STR_PAD_LEFT),
                    $tax->user->UserName,
                    '#B' . $tax->BookingID,
                    $tax->booking->item->ItemName ?? 'N/A',
                    'RM ' . number_format($tax->TaxAmount, 2),
                    $tax->DateCollected->format('Y-m-d'),
                    $tax->DateCollected->format('F'),
                    $tax->DateCollected->format('Y'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}