<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log to a file we know we can write to
$logFile = '/tmp/db_connection.log';
file_put_contents($logFile, "Connection attempt at: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

if (!file_exists(__DIR__ . '/../config.php')) {
    die("Config file not found");
}

require_once __DIR__ . '/../config.php';

file_put_contents($logFile, "Config loaded. Values: " . 
    DB_HOST . ", " . DB_NAME . ", " . DB_USER . "\n", FILE_APPEND);

if (!defined('DB_HOST')) {
    die("DB_HOST not defined");
}

echo "Attempting connection with: " . DB_HOST . ", " . DB_USER . ", " . DB_NAME . "\n";

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    file_put_contents($logFile, "DSN: " . $dsn . "\n", FILE_APPEND);
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    
    file_put_contents($logFile, "Connection successful\n", FILE_APPEND);
} catch (PDOException $e) {
    file_put_contents($logFile, "Connection failed: " . $e->getMessage() . "\n", FILE_APPEND);
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Check error log for details.");
}