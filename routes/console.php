<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Http\Controllers\BookingController;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule auto-complete bookings daily at midnight
Schedule::call(function () {
    $controller = new BookingController();
    $completed = $controller->autoCompleteBookings();
    \Log::info("Auto-completed {$completed} bookings via scheduler");
})->daily()->at('00:00');

// Alternative: using the command
Schedule::command('bookings:auto-complete')->daily()->at('00:00');

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks (Laravel 11)
|--------------------------------------------------------------------------
*/

// Auto-complete expired bookings daily at midnight
Schedule::command('bookings:complete-expired')
    ->daily()
    ->at('00:00');

// Update item availability every hour
Schedule::command('items:update-availability')
    ->hourly();

// Send booking ending reminders daily at 9 AM
Schedule::command('bookings:send-ending-reminders --days=1')
    ->daily()
    ->at('09:00');

// Send same-day booking ending reminders at 8 AM
Schedule::command('bookings:send-ending-reminders --days=0')
    ->daily()
    ->at('08:00');

// Send handover reminders on rental start date at 7 AM
Schedule::command('bookings:send-handover-reminders')
    ->daily()
    ->at('07:00');