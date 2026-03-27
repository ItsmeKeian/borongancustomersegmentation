<?php
// php/chat/admin_fetch_conversation.php
require_once __DIR__ . '/../dbconnect.php';
session_start();
header('Content-Type: application/json; charset=utf-8');

// ADMIN ONLY
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    http_response_code(401);
    echo json_encode(['status' => 0, 'message' => 'Unauthorized']);
    exit;
}

$est_id = isset($_GET['est_id']) ? (int)$_GET['est_id'] : 0;
if ($est_id <= 0) {
    echo json_encode([]);
    exit;
}

$stmt = $mysqli->prepare("
    SELECT id, sender_type, message, created_at
    FROM messages
    WHERE establishment_id = ?
      AND sender_type <> 'bot'  -- hide bot rows (old data)
    ORDER BY created_at ASC, id ASC
");
$stmt->bind_param('i', $est_id);
$stmt->execute();

$res = $stmt->get_result();
$out = [];

while ($row = $res->fetch_assoc()) {
    $out[] = $row;
}
$stmt->close();

echo json_encode($out);
