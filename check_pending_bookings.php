<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Booking;

// Get pending bookings
$bookings = Booking::with(['item', 'user'])
    ->where('Status', 'pending')
    ->whereNull('TotalPaid')
    ->orWhere('TotalPaid', 0)
    ->get();

echo "Pending Bookings Awaiting Payment:\n";
echo str_repeat("=", 80) . "\n";

foreach ($bookings as $booking) {
    echo "\nBooking ID: " . $booking->BookingID . "\n";
    echo "Item: " . $booking->item->ItemName . "\n";
    echo "Item Name Length: " . strlen($booking->item->ItemName) . " characters\n";
    echo "Start Date: " . $booking->StartDate->format('d M') . "\n";
    echo "End Date: " . $booking->EndDate->format('d M Y') . "\n";

    // Simulate the description generation
    $itemName = strlen($booking->item->ItemName) > 40
        ? substr($booking->item->ItemName, 0, 37) . '...'
        : $booking->item->ItemName;

    $billDescription = 'Deposit for ' . $itemName . ' (' . $booking->StartDate->format('d M') . '-' . $booking->EndDate->format('d M Y') . ')';
    $finalDescription = substr($billDescription, 0, 100);

    echo "Generated Description: " . $finalDescription . "\n";
    echo "Description Length: " . strlen($finalDescription) . " characters\n";
    echo "Deposit Amount: RM " . number_format($booking->DepositAmount, 2) . "\n";
    echo str_repeat("-", 80) . "\n";
}

if ($bookings->isEmpty()) {
    echo "No pending bookings found.\n";
}
