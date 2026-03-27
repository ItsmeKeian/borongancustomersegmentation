<?php
require_once __DIR__ . '/../require_login.php';
require_role('Establishment');
require_once __DIR__ . '/../../php/dbconnect.php'; // adapt path

header('Content-Type: application/json');

$estId = $_SESSION['establishment_sid'] ?? null;
if (!$estId) {
    echo json_encode(['success'=>false,'message'=>'No establishment']);
    exit;
}

// Expect ?month=2025-09 (YYYY-MM)
$month = $_GET['month'] ?? date('Y-m');
$start = $month . '-01';
$end = date('Y-m-d', strtotime("{$start} +1 month -1 day"));

$sql = "SELECT id, date, time, title, note, color, icon FROM reminders
        WHERE establishment_id = ? AND date BETWEEN ? AND ? ORDER BY date, time";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iss', $estId, $start, $end);
$stmt->execute();
$res = $stmt->get_result();
$rows = $res->fetch_all(MYSQLI_ASSOC);

$grouped = [];
foreach ($rows as $r) {
    $d = $r['date'];
    if (!isset($grouped[$d])) $grouped[$d] = [];
    $grouped[$d][] = $r;
}

echo json_encode(['success'=>true, 'month'=>$month, 'reminders'=>$grouped]);
