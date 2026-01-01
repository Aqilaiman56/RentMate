<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== Verifying All Existing Users ===\n\n";

// Get all users where email_verified_at is NULL
$unverifiedUsers = User::whereNull('email_verified_at')->get();

if ($unverifiedUsers->isEmpty()) {
    echo "✓ All users are already verified!\n";
    exit(0);
}

echo "Found " . $unverifiedUsers->count() . " unverified user(s):\n\n";

foreach ($unverifiedUsers as $user) {
    echo "- UserID: {$user->UserID}, Name: {$user->UserName}, Email: {$user->Email}\n";
}

echo "\n";
echo "Marking all users as verified...\n\n";

// Update all users to mark them as verified
$updated = User::whereNull('email_verified_at')
    ->update([
        'email_verified_at' => now()
    ]);

echo "✓ SUCCESS: {$updated} user(s) have been marked as verified!\n\n";

// Verify the update
$stillUnverified = User::whereNull('email_verified_at')->count();

if ($stillUnverified === 0) {
    echo "✓ CONFIRMED: All users are now verified!\n";
} else {
    echo "⚠ WARNING: {$stillUnverified} user(s) are still unverified.\n";
}

echo "\n=== Summary ===\n";
$totalUsers = User::count();
$verifiedUsers = User::whereNotNull('email_verified_at')->count();

echo "Total Users: {$totalUsers}\n";
echo "Verified Users: {$verifiedUsers}\n";
echo "Unverified Users: {$stillUnverified}\n";
