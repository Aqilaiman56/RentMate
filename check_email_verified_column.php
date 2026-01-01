<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Checking 'users' table structure ===\n\n";

// Get all columns in users table
$columns = Schema::getColumnListing('users');

echo "Columns in 'users' table:\n";
foreach ($columns as $column) {
    echo "- $column\n";
}

echo "\n";

// Check specifically for email_verified_at
if (in_array('email_verified_at', $columns)) {
    echo "✓ SUCCESS: 'email_verified_at' column EXISTS in the users table!\n";

    // Get column details
    $columnType = DB::select("SHOW COLUMNS FROM users WHERE Field = 'email_verified_at'");
    if (!empty($columnType)) {
        echo "\nColumn details:\n";
        foreach ($columnType[0] as $key => $value) {
            echo "  $key: $value\n";
        }
    }
} else {
    echo "✗ ERROR: 'email_verified_at' column NOT FOUND in the users table!\n";
}
