<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Testing User Registration\n";
echo "=========================\n\n";

try {
    // Test data
    $testEmail = 'test_' . time() . '@example.com';

    echo "1. Creating test user with email: {$testEmail}\n";

    $user = new User();
    $user->name = 'Test User';
    $user->email = $testEmail;
    $user->password = Hash::make('password123');
    $user->UserType = 'Student';
    $user->IsAdmin = 0;
    $user->IsSuspended = 0;

    echo "2. Attempting to save user...\n";
    $user->save();

    echo "✓ SUCCESS! User created with ID: {$user->UserID}\n\n";

    // Verify it was saved
    echo "3. Verifying user was saved to database...\n";
    $savedUser = User::where('Email', $testEmail)->first();

    if ($savedUser) {
        echo "✓ User found in database:\n";
        echo "   UserID: {$savedUser->UserID}\n";
        echo "   UserName: {$savedUser->UserName}\n";
        echo "   Email: {$savedUser->Email}\n";
        echo "   UserType: {$savedUser->UserType}\n";
        echo "   CreatedAt: {$savedUser->CreatedAt}\n";
        echo "   UpdatedAt: {$savedUser->UpdatedAt}\n";
        echo "\n✓ Registration is working correctly!\n";

        // Clean up test user
        echo "\n4. Cleaning up test user...\n";
        $savedUser->delete();
        echo "✓ Test user deleted\n";
    } else {
        echo "✗ User not found in database!\n";
    }

} catch (Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
