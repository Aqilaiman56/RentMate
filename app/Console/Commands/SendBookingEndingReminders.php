<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Notification;
use Carbon\Carbon;

class SendBookingEndingReminders extends Command
{
    protected $signature = 'bookings:send-ending-reminders {--days=1 : Number of days before end date to send reminder}';
    protected $description = 'Send notifications to users whose bookings are ending soon';

    public function handle()
    {
        $days = (int) $this->option('days');
        $targetDate = Carbon::today()->addDays($days);

        $this->info("Checking for bookings ending on {$targetDate->toDateString()}...");

        // Find active bookings ending on the target date
        $bookings = Booking::whereIn('Status', ['confirmed', 'Confirmed', 'ongoing', 'Ongoing'])
            ->whereDate('EndDate', $targetDate)
            ->with(['user', 'item'])
            ->get();

        $notificationsSent = 0;

        foreach ($bookings as $booking) {
            // Check if we already sent a reminder for this booking
            $existingReminder = Notification::where('UserID', $booking->UserID)
                ->where('RelatedID', $booking->BookingID)
                ->where('RelatedType', 'booking')
                ->where('Type', 'booking_ending')
                ->whereDate('CreatedAt', Carbon::today())
                ->exists();

            if ($existingReminder) {
                $this->line("Reminder already sent for booking #{$booking->BookingID}, skipping...");
                continue;
            }

            $itemName = $booking->item->Name ?? 'your rented item';
            $endDate = Carbon::parse($booking->EndDate)->format('F j, Y');

            $title = $days === 0
                ? '⏰ Your Booking Ends Today!'
                : "⏰ Your Booking Ends in {$days} Day" . ($days > 1 ? 's' : '');

            $content = $days === 0
                ? "Reminder: Your rental of \"{$itemName}\" ends today ({$endDate}). Please prepare to return the item to avoid any penalties."
                : "Reminder: Your rental of \"{$itemName}\" will end on {$endDate}. Please make sure to return the item on time to receive your deposit refund.";

            Notification::create([
                'UserID' => $booking->UserID,
                'Type' => 'booking_ending',
                'Title' => $title,
                'Content' => $content,
                'RelatedID' => $booking->BookingID,
                'RelatedType' => 'booking',
                'IsRead' => false,
                'CreatedAt' => now(),
            ]);

            $notificationsSent++;
            $this->line("Sent reminder to user #{$booking->UserID} for booking #{$booking->BookingID}");
        }

        $this->info("Sent {$notificationsSent} booking ending reminder(s).");

        return 0;
    }
}
