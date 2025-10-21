<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\Item;

class BookingObserver
{
    /**
     * Handle the Booking "created" event.
     */
    public function created(Booking $booking)
    {
        $this->updateItemAvailability($booking);
    }

    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking)
    {
        $this->updateItemAvailability($booking);
    }

    /**
     * Handle the Booking "deleted" event.
     */
    public function deleted(Booking $booking)
    {
        $this->updateItemAvailability($booking);
    }

    /**
     * Update item availability based on booking status
     */
    protected function updateItemAvailability(Booking $booking)
    {
        $item = Item::find($booking->ItemID);
        
        if ($item) {
            $item->updateAvailabilityStatus();
        }
    }
}