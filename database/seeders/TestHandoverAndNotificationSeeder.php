<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\Deposit;
use App\Models\Payment;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;

class TestHandoverAndNotificationSeeder extends Seeder
{
    public function run(): void
    {
        // Get a renter and an item (make sure they exist)
        $renter = User::where('IsAdmin', false)->first();
        $item = Item::with('user')->where('UserID', '!=', $renter?->UserID)->first();

        if (!$renter || !$item) {
            $this->command->error('Need at least one user and one item owned by different users!');
            return;
        }

        $owner = $item->user;

        $this->command->info("Creating test bookings...");
        $this->command->info("Renter: {$renter->UserName} (ID: {$renter->UserID})");
        $this->command->info("Owner: {$owner->UserName} (ID: {$owner->UserID})");
        $this->command->info("Item: {$item->ItemName} (ID: {$item->ItemID})");

        // 1. Booking for HANDOVER TEST - starts today, confirmed status
        $handoverBooking = Booking::create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'Quantity' => 1,
            'StartDate' => Carbon::today(),
            'EndDate' => Carbon::today()->addDays(5),
            'TotalAmount' => $item->PricePerDay * 5,
            'DepositAmount' => $item->DepositAmount ?? 50.00,
            'ServiceFeeAmount' => 1.00,
            'TotalPaid' => ($item->DepositAmount ?? 50.00) + 1.00,
            'Status' => 'confirmed',
            'ReturnConfirmed' => false,
            'BookingDate' => Carbon::now()->subDays(2),
            'OwnerHandoverConfirmed' => false,
            'RenterHandoverConfirmed' => false,
            'HandoverConfirmedAt' => null,
        ]);

        // Create deposit for handover booking
        Deposit::create([
            'BookingID' => $handoverBooking->BookingID,
            'DepositAmount' => $item->DepositAmount ?? 50.00,
            'Status' => 'held',
            'DateCollected' => Carbon::now()->subDays(2),
        ]);

        // Create payment for handover booking
        Payment::create([
            'BookingID' => $handoverBooking->BookingID,
            'Amount' => ($item->DepositAmount ?? 50.00) + 1.00,
            'Status' => 'successful',
            'PaymentMethod' => 'FPX',
            'TransactionID' => 'TXN-HANDOVER-' . time(),
            'BillCode' => 'BILL-HANDOVER-' . time(),
            'PaymentDate' => Carbon::now()->subDays(2),
            'CreatedAt' => Carbon::now()->subDays(2),
        ]);

        $this->command->info("Created Handover Test Booking #" . $handoverBooking->BookingID);

        // 2. Booking for ENDING SOON TEST - ends tomorrow
        $endingSoonBooking = Booking::create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'Quantity' => 1,
            'StartDate' => Carbon::today()->subDays(4),
            'EndDate' => Carbon::tomorrow(),
            'TotalAmount' => $item->PricePerDay * 5,
            'DepositAmount' => $item->DepositAmount ?? 50.00,
            'ServiceFeeAmount' => 1.00,
            'TotalPaid' => ($item->DepositAmount ?? 50.00) + 1.00,
            'Status' => 'ongoing',
            'ReturnConfirmed' => false,
            'BookingDate' => Carbon::now()->subDays(5),
            'OwnerHandoverConfirmed' => true,
            'RenterHandoverConfirmed' => true,
            'HandoverConfirmedAt' => Carbon::today()->subDays(4),
        ]);

        // Create deposit for ending soon booking
        Deposit::create([
            'BookingID' => $endingSoonBooking->BookingID,
            'DepositAmount' => $item->DepositAmount ?? 50.00,
            'Status' => 'held',
            'DateCollected' => Carbon::now()->subDays(5),
        ]);

        // Create payment for ending soon booking
        Payment::create([
            'BookingID' => $endingSoonBooking->BookingID,
            'Amount' => ($item->DepositAmount ?? 50.00) + 1.00,
            'Status' => 'successful',
            'PaymentMethod' => 'FPX',
            'TransactionID' => 'TXN-ENDING-' . time(),
            'BillCode' => 'BILL-ENDING-' . time(),
            'PaymentDate' => Carbon::now()->subDays(5),
            'CreatedAt' => Carbon::now()->subDays(5),
        ]);

        $this->command->info("Created Ending Soon Test Booking #" . $endingSoonBooking->BookingID);

        // 3. Create notifications for testing

        // Handover reminder notification for renter
        Notification::create([
            'UserID' => $renter->UserID,
            'Type' => 'handover_reminder',
            'Title' => '📦 Rental Starts Today - Confirm Handover',
            'Content' => "Your rental for \"{$item->ItemName}\" starts today. Please meet with the owner to receive the item and confirm the handover in the booking details.",
            'RelatedID' => $handoverBooking->BookingID,
            'RelatedType' => 'booking',
            'IsRead' => false,
            'CreatedAt' => now(),
        ]);

        // Handover reminder notification for owner
        Notification::create([
            'UserID' => $owner->UserID,
            'Type' => 'handover_reminder',
            'Title' => '📦 Rental Starts Today - Confirm Handover',
            'Content' => "The rental for \"{$item->ItemName}\" starts today. Please meet with {$renter->UserName} to hand over the item and confirm the handover in the booking details.",
            'RelatedID' => $handoverBooking->BookingID,
            'RelatedType' => 'booking',
            'IsRead' => false,
            'CreatedAt' => now(),
        ]);

        // Booking ending soon notification for renter
        Notification::create([
            'UserID' => $renter->UserID,
            'Type' => 'booking_ending',
            'Title' => '⏰ Your Booking Ends in 1 Day',
            'Content' => "Reminder: Your rental of \"{$item->ItemName}\" will end on " . Carbon::tomorrow()->format('F j, Y') . ". Please make sure to return the item on time to receive your deposit refund.",
            'RelatedID' => $endingSoonBooking->BookingID,
            'RelatedType' => 'booking',
            'IsRead' => false,
            'CreatedAt' => now(),
        ]);

        $this->command->info("Created test notifications");

        $this->command->newLine();
        $this->command->info("=== TEST DATA SUMMARY ===");
        $this->command->info("1. HANDOVER TEST:");
        $this->command->info("   - Booking ID: {$handoverBooking->BookingID}");
        $this->command->info("   - Login as Renter ({$renter->Email}) to see 'Confirm Item Received' button");
        $this->command->info("   - Login as Owner ({$owner->Email}) to see 'Confirm Item Handed Over' button");
        $this->command->info("   - URL: /booking/{$handoverBooking->BookingID}");
        $this->command->newLine();
        $this->command->info("2. BOOKING ENDING NOTIFICATION:");
        $this->command->info("   - Booking ID: {$endingSoonBooking->BookingID}");
        $this->command->info("   - Login as Renter ({$renter->Email}) to see notification");
        $this->command->info("   - Check /notifications page");
        $this->command->newLine();
    }
}
