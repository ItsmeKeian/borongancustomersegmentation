<?php
// php/chat/fetch_unread_count.php
require_once __DIR__ . '/../dbconnect.php';
session_start();
header('Content-Type: application/json; charset=utf-8');

// ADMIN ONLY
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    http_response_code(401);
    echo json_encode(['status' => 0, 'message' => 'Unauthorized']);
    exit;
}

$sql = "
    SELECT 
        e.establishment_sid AS establishment_id,
        e.business_name,
        COUNT(m.id) AS cnt
    FROM establishment e
    LEFT JOIN messages m 
        ON m.establishment_id = e.establishment_sid
       AND m.sender_type = 'user'
       AND m.is_read = 0
    GROUP BY e.establishment_sid, e.business_name
    ORDER BY e.business_name ASC
";

$res = $mysqli->query($sql);

$out = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $out[] = $row;
    }
    echo json_encode($out);
} else {
    echo json_encode(['status' => 0, 'message' => 'DB Error: '.$mysqli->error]);
}
