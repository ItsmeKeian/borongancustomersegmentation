<?php
// php/reminder/edit_reminder.php
require_once __DIR__ . '/../require_login.php';
require_once __DIR__ . '/../dbconnect.php';
header('Content-Type: application/json');

$est_sid  = $_SESSION['establishment_sid'] ?? null;
$est_name = $_SESSION['business_name'] ?? null;

$id = intval($_POST['id'] ?? 0);
$message = trim($_POST['message'] ?? '');

if (!$id || $message === '') {
    echo json_encode(['status' => 'error', 'message' => 'missing']);
    exit;
}

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
        $stmt = $conn->prepare("UPDATE reminders SET message = ? WHERE id = ? AND $est_col = ?");
        $stmt->bind_param('sis', $message, $id, $estVal);
    } else {
        $estVal = $est_name ?? $est_sid;
        $stmt = $conn->prepare("UPDATE reminders SET message = ? WHERE id = ? AND $est_col = ?");
        $stmt->bind_param('sis', $message, $id, $estVal);
    }
} else {
    $stmt = $conn->prepare("UPDATE reminders SET message = ? WHERE id = ?");
    $stmt->bind_param('si', $message, $id);
}

if ($stmt->execute()) echo json_encode(['status' => 'success']);
else echo json_encode(['status' => 'error', 'message' => $conn->error]);
