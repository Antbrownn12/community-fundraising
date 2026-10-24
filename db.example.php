<?php
// Copy this file to db.php and enter your local MySQL credentials.
// Keep db.php out of GitHub.

$host = '127.0.0.1';
$port = 3306;
$database = 'community_fundraising';
$username = 'YOUR_MYSQL_USERNAME';
$password = 'YOUR_MYSQL_PASSWORD';

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('Database connection failed. Check your connection settings.');
}