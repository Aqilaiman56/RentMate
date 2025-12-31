<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Current database configuration:\n";
echo "Database: " . config('database.connections.mysql.database') . "\n";
echo "Host: " . config('database.connections.mysql.host') . "\n";
echo "Username: " . config('database.connections.mysql.username') . "\n";

try {
    $pdo = DB::connection()->getPdo();
    $dbName = DB::connection()->getDatabaseName();
    echo "\nActual connected database: " . $dbName . "\n";
} catch (Exception $e) {
    echo "\nError connecting: " . $e->getMessage() . "\n";
}
