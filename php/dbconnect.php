<?php
// Force PHP to use Philippine Time
date_default_timezone_set('Asia/Manila');

// Database credentials
define("DB_HOST", "localhost");
define("DB_NAME", "custsegmentation");
define("DB_USER", "root");
define("DB_PASS", "");

// Existing MySQLi connection (KEEP THIS)
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check MySQLi connection
if ($conn->connect_error) {
    die("MySQLi connection failed: " . $conn->connect_error);
}

// IMPORTANT: Create alias for compatibility
$mysqli = $conn;   // <-- THIS FIXES YOUR CHAT SYSTEM

// Set timezone
$conn->query("SET time_zone = '+08:00'");

// PDO connection (logging or other features)
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET time_zone = '+08:00'");
} catch (PDOException $e) {
    error_log("PDO connection failed: " . $e->getMessage());
    $pdo = null;
}
?>
