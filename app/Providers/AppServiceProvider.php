<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Booking;
use App\Observers\BookingObserver;
use App\View\Composers\AdminSidebarComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         // Register Booking Observer
        Booking::observe(BookingObserver::class);

        // Register Admin Sidebar View Composer
        View::composer('layouts.admin', AdminSidebarComposer::class);
    }
}
