<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\User;
use App\Models\Item;
use App\Models\Penalty;
use App\Models\Booking;
use App\Models\Deposit;

class AdminSidebarComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Only compute statistics if user is admin
        if (auth()->check() && auth()->user()->IsAdmin) {
            $view->with([
                'totalUsers' => User::where('IsAdmin', 0)->count(),
                'totalListings' => Item::count(),
                'totalDeposits' => Deposit::whereIn('Status', ['held', 'refunded'])->sum('DepositAmount') ?? 0,
                'totalReports' => Penalty::count(),
                'totalPenalties' => Penalty::whereNotNull('PenaltyAmount')->where('PenaltyAmount', '>', 0)->count(),
                'serviceFeeCount' => Booking::whereIn('Status', ['completed', 'approved'])->count(),
            ]);
        }
    }
}
