<?php
require_once __DIR__ . '/../dbconnect.php';
session_start();

// Only establishment users
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Establishment') {
    exit;
}

$estId = $_SESSION['user_id'];

// Mark all admin messages as READ
$conn->query("
    UPDATE messages
    SET is_read = 1
    WHERE establishment_id = $estId
      AND sender_type = 'admin'
");
