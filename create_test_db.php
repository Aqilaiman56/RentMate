<?php
// Create test database for unit testing
try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS rentmate_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    echo "✓ Test database 'rentmate_test' created successfully!\n";
} catch (PDOException $e) {
    echo "✗ Error creating database: " . $e->getMessage() . "\n";
    exit(1);
}
