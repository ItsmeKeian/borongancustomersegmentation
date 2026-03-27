<?php
// php/reminder/save_reminder.php
require_once __DIR__ . '/../require_login.php';
require_once __DIR__ . '/../dbconnect.php';
header('Content-Type: application/json');

// session values
$est_sid  = $_SESSION['establishment_sid'] ?? null;
$est_name = $_SESSION['business_name'] ?? null;

if (!$est_sid && !$est_name) {
    echo json_encode(['status' => 'error', 'message' => 'not logged']);
    exit;
}

$date = $_POST['date'] ?? null;
$message = trim($_POST['message'] ?? '');

if (!$date || !$message) {
    echo json_encode(['status' => 'error', 'message' => 'missing']);
    exit;
}

// determine which establishment column your reminders table has
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
if (!$est_col) {
    // fallback: insert without establishment filtering (not ideal)
    $stmt = $conn->prepare("INSERT INTO reminders (reminder_date, message) VALUES (?, ?)");
    $stmt->bind_param('ss', $date, $message);
} else {
    if (strpos($est_col, 'id') !== false || strpos($est_col, 'sid') !== false) {
        // numeric column
        $est_val = $est_sid ? intval($est_sid) : 0;
        $stmt = $conn->prepare("INSERT INTO reminders ($est_col, reminder_date, message) VALUES (?,?,?)");
        $stmt->bind_param('iss', $est_val, $date, $message);
    } else {
        // string column
        $est_val = $est_name ?? $est_sid;
        $stmt = $conn->prepare("INSERT INTO reminders ($est_col, reminder_date, message) VALUES (?,?,?)");
        $stmt->bind_param('sss', $est_val, $date, $message);
    }
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
}
