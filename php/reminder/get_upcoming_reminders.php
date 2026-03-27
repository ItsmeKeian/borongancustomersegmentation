<?php
// php/reminder/get_upcoming_reminders.php
require_once __DIR__ . '/../require_login.php';
require_once __DIR__ . '/../dbconnect.php';
header('Content-Type: application/json');

$est_sid  = $_SESSION['establishment_sid'] ?? null;
$est_name = $_SESSION['business_name'] ?? null;
$today = date('Y-m-d');

function get_est_column($conn) {
    $candidates = ["establishment_id","establishment","establishment_name","establishment_sid"];
    $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'reminders'
              AND COLUMN_NAME IN ('" . implode("','", $candidates) . "')
            LIMIT 1";
    $res = $conn->query($sql);
    if ($res && $row = $res->fetch_assoc()) return $row['COLUMN_NAME'];
    return null;
}

$est_col = get_est_column($conn);

if ($est_col) {
    if (strpos($est_col, 'id') !== false || strpos($est_col, 'sid') !== false) {
        $estVal = $est_sid ? intval($est_sid) : 0;
        $stmt = $conn->prepare("SELECT id, reminder_date, message FROM reminders WHERE $est_col = ? AND reminder_date >= ? ORDER BY reminder_date ASC LIMIT 50");
        $stmt->bind_param('is', $estVal, $today);
    } else {
        $estVal = $est_name ?? $est_sid;
        $stmt = $conn->prepare("SELECT id, reminder_date, message FROM reminders WHERE $est_col = ? AND reminder_date >= ? ORDER BY reminder_date ASC LIMIT 50");
        $stmt->bind_param('ss', $estVal, $today);
    }
} else {
    $stmt = $conn->prepare("SELECT id, reminder_date, message FROM reminders WHERE reminder_date >= ? ORDER BY reminder_date ASC LIMIT 50");
    $stmt->bind_param('s', $today);
}

$stmt->execute();
$res = $stmt->get_result();
$out = [];
while ($r = $res->fetch_assoc()) $out[] = $r;
echo json_encode($out);
