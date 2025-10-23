<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Item;
use App\Models\Booking;
use Carbon\Carbon;

echo "Testing Booking Projector Set for User 10...\n\n";

try {
    // Step 1: Find or create user with ID 10
    echo "=== STEP 1: Finding/Creating User 10 ===\n";

    $user = User::find(10);

    if (!$user) {
        echo "User 10 not found, creating test user...\n";
        $user = User::create([
            'UserID' => 10,
            'UserName' => 'Test User 10',
            'Email' => 'testuser10@example.com',
            'PasswordHash' => bcrypt('password'),
            'UserType' => 'Borrower',
            'IsAdmin' => 0
        ]);
        echo "✅ Created test user with ID: {$user->UserID}\n";
    } else {
        echo "✅ Found existing user: {$user->UserName} (ID: {$user->UserID})\n";
    }

    // Step 2: Find the projector set item
    echo "\n=== STEP 2: Finding Projector Set Item ===\n";

    $projector = Item::where('ItemName', 'Projector Set')->first();

    if (!$projector) {
        echo "❌ Projector Set not found. Please run the seeder first.\n";
        exit(1);
    }

    echo "✅ Found projector: {$projector->ItemName} (ID: {$projector->ItemID})\n";
    echo "   - Quantity: {$projector->Quantity}\n";
    echo "   - Available Quantity: {$projector->AvailableQuantity}\n";
    echo "   - Deposit: RM{$projector->DepositAmount}\n";
    echo "   - Price per day: RM{$projector->PricePerDay}\n";

    // Step 3: Create booking for 2 days
    echo "\n=== STEP 3: Creating Booking for 2 Days ===\n";

    $startDate = Carbon::now()->addDays(1);
    $endDate = Carbon::now()->addDays(2); // 2 days total

    $booking = Booking::create([
        'UserID' => $user->UserID,
        'ItemID' => $projector->ItemID,
        'StartDate' => $startDate,
        'EndDate' => $endDate,
        'TotalAmount' => $projector->PricePerDay * 2, // 2 days
        'DepositAmount' => $projector->DepositAmount,
        'Status' => 'confirmed', // Directly set to confirmed to trigger availability update
        'BookingDate' => now()
    ]);

    echo "✅ Booking created with ID: {$booking->BookingID}\n";
    echo "   - Start Date: {$startDate->format('Y-m-d')}\n";
    echo "   - End Date: {$endDate->format('Y-m-d')}\n";
    echo "   - Total Amount: RM{$booking->TotalAmount}\n";
    echo "   - Deposit Amount: RM{$booking->DepositAmount}\n";
    echo "   - Status: {$booking->Status}\n";

    // Step 4: Update item availability (simulate what the observer would do)
    echo "\n=== STEP 4: Updating Item Availability ===\n";

    $projector->refresh(); // Reload from database
    $projector->updateAvailableQuantity();

    echo "✅ Item availability updated\n";
    echo "   - Quantity: {$projector->Quantity}\n";
    echo "   - Available Quantity: {$projector->AvailableQuantity}\n";
    echo "   - Availability: " . ($projector->Availability ? 'Available' : 'Unavailable') . "\n";

    // Step 5: Verify the availability change
    echo "\n=== STEP 5: Verifying Availability Change ===\n";

    if ($projector->AvailableQuantity === 0 && !$projector->Availability) {
        echo "✅ SUCCESS: Projector set is now UNAVAILABLE (AvailableQuantity: 0)\n";
    } else {
        echo "❌ FAILURE: Projector set availability not updated correctly\n";
        echo "   - Expected AvailableQuantity: 0, Got: {$projector->AvailableQuantity}\n";
        echo "   - Expected Availability: false, Got: " . ($projector->Availability ? 'true' : 'false') . "\n";
    }

    // Step 6: Show booking details
    echo "\n=== STEP 6: Booking Summary ===\n";
    echo "User: {$user->UserName} (ID: {$user->UserID})\n";
    echo "Item: {$projector->ItemName} (ID: {$projector->ItemID})\n";
    echo "Booking ID: {$booking->BookingID}\n";
    echo "Duration: 2 days ({$startDate->format('d M Y')} to {$endDate->format('d M Y')})\n";
    echo "Status: {$booking->Status}\n";
    echo "Item Availability: " . ($projector->Availability ? 'Available' : 'Unavailable') . "\n";

    echo "\n🎉 Booking test completed!\n";

} catch (Exception $e) {
    echo "❌ Error during testing: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
