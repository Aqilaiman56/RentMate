<?php
// Drop and recreate test database
try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("DROP DATABASE IF EXISTS rentmate_test");
    $pdo->exec("CREATE DATABASE rentmate_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    echo "✓ Test database 'rentmate_test' recreated successfully!\n";
} catch (PDOException $e) {
    echo "✗ Error recreating database: " . $e->getMessage() . "\n";
    exit(1);
}
