<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Notification;
use Carbon\Carbon;

class SendHandoverReminders extends Command
{
    protected $signature = 'bookings:send-handover-reminders';
    protected $description = 'Send reminders to confirm item handover on rental start date';

    public function handle()
    {
        $today = Carbon::today();

        $this->info("Checking for bookings starting today ({$today->toDateString()}) that need handover confirmation...");

        // Find confirmed bookings starting today that haven't completed handover
        $bookings = Booking::whereIn('Status', ['confirmed', 'Confirmed'])
            ->whereDate('StartDate', $today)
            ->where(function ($query) {
                $query->where('OwnerHandoverConfirmed', false)
                    ->orWhere('RenterHandoverConfirmed', false);
            })
            ->with(['user', 'item.user'])
            ->get();

        $notificationsSent = 0;

        foreach ($bookings as $booking) {
            // Check if we already sent a handover reminder today
            $existingReminder = Notification::where('RelatedID', $booking->BookingID)
                ->where('RelatedType', 'booking')
                ->where('Type', 'handover_reminder')
                ->whereDate('CreatedAt', $today)
                ->exists();

            if ($existingReminder) {
                $this->line("Reminder already sent for booking #{$booking->BookingID}, skipping...");
                continue;
            }

            $itemName = $booking->item->ItemName ?? 'the rented item';

            // Notify owner if they haven't confirmed
            if (!$booking->OwnerHandoverConfirmed) {
                Notification::create([
                    'UserID' => $booking->item->UserID,
                    'Type' => 'handover_reminder',
                    'Title' => '📦 Rental Starts Today - Confirm Handover',
                    'Content' => "The rental for \"{$itemName}\" starts today. Please meet with {$booking->user->UserName} to hand over the item and confirm the handover in the booking details.",
                    'RelatedID' => $booking->BookingID,
                    'RelatedType' => 'booking',
                    'IsRead' => false,
                    'CreatedAt' => now(),
                ]);
                $notificationsSent++;
                $this->line("Sent handover reminder to owner for booking #{$booking->BookingID}");
            }

            // Notify renter if they haven't confirmed
            if (!$booking->RenterHandoverConfirmed) {
                Notification::create([
                    'UserID' => $booking->UserID,
                    'Type' => 'handover_reminder',
                    'Title' => '📦 Rental Starts Today - Confirm Handover',
                    'Content' => "Your rental for \"{$itemName}\" starts today. Please meet with the owner to receive the item and confirm the handover in the booking details.",
                    'RelatedID' => $booking->BookingID,
                    'RelatedType' => 'booking',
                    'IsRead' => false,
                    'CreatedAt' => now(),
                ]);
                $notificationsSent++;
                $this->line("Sent handover reminder to renter for booking #{$booking->BookingID}");
            }
        }

        $this->info("Sent {$notificationsSent} handover reminder(s).");

        return 0;
    }
}
