<?php
// php/chat/mark_read.php
require_once __DIR__ . '/../dbconnect.php';
session_start();
header('Content-Type: application/json; charset=utf-8');

// ADMIN ONLY
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    http_response_code(401);
    echo json_encode(['status' => 0, 'message' => 'Unauthorized']);
    exit;
}

$est_id = isset($_POST['est_id']) ? (int)$_POST['est_id'] : 0;
if ($est_id <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 0, 'message' => 'Invalid establishment ID']);
    exit;
}

$stmt = $mysqli->prepare("
    UPDATE messages
       SET is_read = 1
     WHERE establishment_id = ?
       AND sender_type = 'user'
");
$stmt->bind_param('i', $est_id);
$ok = $stmt->execute();
$stmt->close();

echo json_encode(['status' => $ok ? 1 : 0]);
