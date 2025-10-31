<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceFee;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceFeesController extends Controller
{
    public function index(Request $request)
    {
        // Get year filter or default to current year
        $year = $request->input('year', date('Y'));

        // Get monthly breakdown
        $monthlyServiceFees = ServiceFee::select(
                DB::raw('MONTH(DateCollected) as month'),
                DB::raw('YEAR(DateCollected) as year'),
                DB::raw('SUM(ServiceFeeAmount) as total'),
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
        $totalServiceFees = ServiceFee::whereYear('DateCollected', $year)->sum('ServiceFeeAmount');
        $totalTransactions = ServiceFee::whereYear('DateCollected', $year)->count();
        $averagePerMonth = $monthlyServiceFees->count() > 0 ? $totalServiceFees / $monthlyServiceFees->count() : 0;

        // Get all available years for filter
        $availableYears = ServiceFee::select(DB::raw('YEAR(DateCollected) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Get recent transactions
        $recentServiceFees = ServiceFee::with(['booking.item', 'user'])
            ->whereYear('DateCollected', $year)
            ->orderBy('DateCollected', 'desc')
            ->limit(20)
            ->get();

        return view('admin.service-fees', compact(
            'monthlyServiceFees',
            'totalServiceFees',
            'totalTransactions',
            'averagePerMonth',
            'year',
            'availableYears',
            'recentServiceFees'
        ));
    }

    public function export(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $serviceFees = ServiceFee::with(['booking.item', 'user'])
            ->whereYear('DateCollected', $year)
            ->orderBy('DateCollected', 'desc')
            ->get();

        $filename = 'service_fees_export_' . $year . '_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($serviceFees) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Service Fee ID', 'User', 'Booking ID', 'Item', 'Service Fee Amount', 'Date', 'Month', 'Year']);

            foreach ($serviceFees as $serviceFee) {
                fputcsv($file, [
                    '#SF' . str_pad($serviceFee->ServiceFeeID, 4, '0', STR_PAD_LEFT),
                    $serviceFee->user->UserName,
                    '#B' . $serviceFee->BookingID,
                    $serviceFee->booking->item->ItemName ?? 'N/A',
                    'RM ' . number_format($serviceFee->ServiceFeeAmount, 2),
                    $serviceFee->DateCollected->format('Y-m-d'),
                    $serviceFee->DateCollected->format('F'),
                    $serviceFee->DateCollected->format('Y'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
