<?php
require 'vendor/autoload.php';

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=rentmate_test', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check tables
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);

    echo "=== Tables in rentmate_test ===\n";
    foreach ($tables as $table) {
        echo "- $table\n";
    }

    // Check users table structure
    if (in_array('users', $tables)) {
        echo "\n=== Users table structure ===\n";
        $columns = $pdo->query('DESCRIBE users')->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $col) {
            echo "{$col['Field']}: {$col['Type']}\n";
        }
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
