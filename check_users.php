<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Current database: " . config('database.connections.mysql.database') . "\n";
echo "===========================================\n\n";

try {
    $dbName = DB::connection()->getDatabaseName();
    echo "Connected to: " . $dbName . "\n\n";

    $users = DB::table('users')->select('id', 'name', 'email', 'role')->get();

    echo "Total users in '" . $dbName . "': " . $users->count() . "\n\n";

    if ($users->count() > 0) {
        echo "Users list:\n";
        echo "--------------------------------------------\n";
        foreach ($users as $user) {
            echo "ID: {$user->id} | Email: {$user->email} | Role: {$user->role}\n";
        }
    } else {
        echo "No users found in database!\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
