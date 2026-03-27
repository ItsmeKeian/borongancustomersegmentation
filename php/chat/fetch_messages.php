<?php
require_once __DIR__ . '/../dbconnect.php';
session_start();
header('Content-Type: application/json');

// Security check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Establishment') {
    http_response_code(401);
    echo json_encode([]);
    exit;
}

$estId = $_SESSION['user_id'] ?? 0;
if (!$estId) {
    http_response_code(400);
    echo json_encode([]);
    exit;
}

// Poll for new messages since last ID
$since = isset($_GET['since']) ? intval($_GET['since']) : 0;

$sql = "
    SELECT id, sender_type, message, created_at 
    FROM messages
    WHERE establishment_id = ?
";

if ($since > 0) {
    $sql .= " AND id > ?";
}

$sql .= " ORDER BY id ASC";

$stmt = $conn->prepare($sql);

if ($since > 0) {
    $stmt->bind_param("ii", $estId, $since);
} else {
    $stmt->bind_param("i", $estId);
}

$stmt->execute();
$res = $stmt->get_result();

$messages = [];

while ($row = $res->fetch_assoc()) {
    $messages[] = [
        "id" => intval($row["id"]),
        "sender_type" => $row["sender_type"],
        "message" => $row["message"],
        "date" => date("m/d/Y", strtotime($row["created_at"])),
        "time" => date("h:i A", strtotime($row["created_at"]))
    ];
}

echo json_encode($messages);
exit;
