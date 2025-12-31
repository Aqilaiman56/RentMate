<?php

try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
    $stmt = $pdo->query('SHOW DATABASES');
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "Available databases in your MySQL:\n";
    echo "=====================================\n";
    foreach($databases as $db) {
        if (strpos($db, 'rentmate') !== false) {
            echo ">>> {$db} <<<  (RentMate related)\n";
        } else {
            echo "    {$db}\n";
        }
    }

    echo "\n\nTables in 'rentmate' database:\n";
    echo "=====================================\n";
    $pdo->query('USE rentmate');
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    foreach($tables as $table) {
        if ($table === 'users') {
            echo ">>> {$table} <<<  (User authentication table)\n";
        } else {
            echo "    {$table}\n";
        }
    }

    echo "\n\nUsers count in rentmate.users:\n";
    echo "=====================================\n";
    $count = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    echo "Total users: {$count}\n";

    echo "\n\nChecking if 'mhdaqilaiman@gmail.com' exists:\n";
    echo "=====================================\n";
    $stmt = $pdo->prepare('SELECT UserID, UserName, Email, UserType FROM users WHERE Email = ?');
    $stmt->execute(['mhdaqilaiman@gmail.com']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "✓ FOUND!\n";
        echo "UserID: {$user['UserID']}\n";
        echo "UserName: {$user['UserName']}\n";
        echo "Email: {$user['Email']}\n";
        echo "UserType: {$user['UserType']}\n";
    } else {
        echo "✗ NOT FOUND\n";
    }

} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
