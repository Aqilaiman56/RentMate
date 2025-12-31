<?php

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=rentmate', 'root', '');

    echo "Checking timestamp columns in users table:\n";
    echo "==========================================\n\n";

    $columns = $pdo->query('DESCRIBE users')->fetchAll(PDO::FETCH_ASSOC);

    $hasCreatedAt = false;
    $hasUpdatedAt = false;

    echo "All columns:\n";
    foreach($columns as $col) {
        $name = $col['Field'];
        $type = $col['Type'];

        if (strtolower($name) === 'createdat' || $name === 'CreatedAt' || $name === 'created_at') {
            echo "  ✓ {$name} ({$type}) - FOUND\n";
            $hasCreatedAt = true;
        } elseif (strtolower($name) === 'updatedat' || $name === 'UpdatedAt' || $name === 'updated_at') {
            echo "  ✓ {$name} ({$type}) - FOUND\n";
            $hasUpdatedAt = true;
        } else {
            echo "    {$name} ({$type})\n";
        }
    }

    echo "\n\nSummary:\n";
    echo "CreatedAt column exists: " . ($hasCreatedAt ? "YES" : "NO") . "\n";
    echo "UpdatedAt column exists: " . ($hasUpdatedAt ? "YES" : "NO") . "\n";

    if (!$hasUpdatedAt) {
        echo "\n⚠ WARNING: UpdatedAt column is missing!\n";
        echo "You need to either:\n";
        echo "1. Add UpdatedAt column to the database, OR\n";
        echo "2. Disable timestamps in the User model\n";
    }

} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
