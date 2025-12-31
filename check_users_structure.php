<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Current database: " . config('database.connections.mysql.database') . "\n";
echo "===========================================\n\n";

try {
    $dbName = DB::connection()->getDatabaseName();
    echo "Connected to: " . $dbName . "\n\n";

    // Show all tables
    $tables = DB::select("SHOW TABLES");
    echo "Tables in database:\n";
    foreach ($tables as $table) {
        $tableName = $table->{"Tables_in_" . $dbName};
        echo "  - {$tableName}\n";
    }

    echo "\n--------------------------------------------\n";
    echo "Users table structure:\n";
    echo "--------------------------------------------\n";

    $columns = DB::select("DESCRIBE users");
    foreach ($columns as $column) {
        echo "{$column->Field} ({$column->Type})\n";
    }

    echo "\n--------------------------------------------\n";
    echo "Sample users data:\n";
    echo "--------------------------------------------\n";

    $users = DB::table('users')->limit(10)->get();
    echo "Total users: " . DB::table('users')->count() . "\n\n";

    if ($users->count() > 0) {
        foreach ($users as $user) {
            echo json_encode($user, JSON_PRETTY_PRINT) . "\n\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
