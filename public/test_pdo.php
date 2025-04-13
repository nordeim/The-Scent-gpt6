<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config.php';

echo "Testing PDO connection variations...\n";

$tests = [
    "Standard TCP" => "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
    "TCP with charset" => "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
    "Socket without charset" => "mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname=" . DB_NAME,
    "Socket with charset" => "mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname=" . DB_NAME . ";charset=utf8mb4",
    "Local socket" => "mysql:host=localhost;dbname=" . DB_NAME . ";charset=utf8mb4"
];

foreach ($tests as $name => $dsn) {
    echo "\nTrying $name\nDSN: $dsn\n";
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        $stmt = $pdo->query("SELECT 1");
        echo "SUCCESS: Connection worked!\n";
    } catch (PDOException $e) {
        echo "FAILED: " . $e->getMessage() . "\n";
    }
}