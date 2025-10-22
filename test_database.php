<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Item;
use App\Models\Booking;

echo "Testing Database Connection and Data...\n\n";

try {
    // Test users
    echo "=== USERS ===\n";
    $users = User::take(3)->get();
    foreach ($users as $user) {
        echo "ID: {$user->UserID}, Name: {$user->UserName}, Email: {$user->Email}\n";
    }

    echo "\n=== ITEMS ===\n";
    $items = Item::take(3)->get();
    foreach ($items as $item) {
        echo "ID: {$item->ItemID}, Name: {$item->ItemName}, Price: RM{$item->PricePerDay}/day, Deposit: RM{$item->DepositAmount}\n";
    }

    echo "\n=== BOOKINGS ===\n";
    $bookings = Booking::with(['user', 'item'])->take(3)->get();
    foreach ($bookings as $booking) {
        echo "ID: {$booking->BookingID}, User: {$booking->user->UserName}, Item: {$booking->item->ItemName}, Status: {$booking->Status}\n";
    }

    echo "\n✅ Database connection successful!\n";

} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}
