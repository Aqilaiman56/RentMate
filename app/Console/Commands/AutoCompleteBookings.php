<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\BookingController;

class AutoCompleteBookings extends Command
{
    protected $signature = 'bookings:auto-complete';
    protected $description = 'Auto-complete bookings and refund deposits after rental period ends';

    public function handle()
    {
        $this->info('Starting auto-complete process...');
        
        $controller = new BookingController();
        $completed = $controller->autoCompleteBookings();
        
        $this->info("Completed {$completed} bookings and processed deposit refunds.");
        
        return 0;
    }
}