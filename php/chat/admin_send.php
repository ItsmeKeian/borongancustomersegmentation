<?php
require_once __DIR__ . '/../dbconnect.php';
session_start();
header('Content-Type: application/json; charset=utf-8');

// ADMIN ONLY
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    http_response_code(401);
    echo json_encode(['status' => 0, 'message' => 'Unauthorized']);
    exit;
}

$admin_id = $_SESSION['user_id'] ?? null;

$data = json_decode(file_get_contents('php://input'), true);

$est_id = isset($data['est_id']) ? (int)$data['est_id'] : 0;
$msg    = isset($data['message']) ? trim($data['message']) : '';

if ($est_id <= 0 || $msg === '') {
    http_response_code(400);
    echo json_encode(['status' => 0, 'message' => 'Invalid data']);
    exit;
}

// ✔ Use $conn (consistent DB connection)
$stmt = $conn->prepare("
    INSERT INTO messages (establishment_id, sender_type, sender_id, message, is_read)
    VALUES (?, 'admin', ?, ?, 0)
");
$stmt->bind_param('iis', $est_id, $admin_id, $msg);
$ok = $stmt->execute();
$stmt->close();

echo json_encode([
    'status'  => $ok ? 1 : 0,
    'message' => $ok ? 'Reply sent' : 'DB error: '.$conn->error
]);
