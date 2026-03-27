<?php
// php/reminder/fetch_reminder.php
require_once __DIR__ . '/../require_login.php';
require_once __DIR__ . '/../dbconnect.php';
header('Content-Type: application/json');

$est_sid  = $_SESSION['establishment_sid'] ?? null;
$est_name = $_SESSION['business_name'] ?? null;

if (!$est_sid && !$est_name) {
    // still allow id fetch (if admin) but generally return empty
    // echo json_encode([]); exit;
    // we'll continue but ensure queries include nothing if no session
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

// 1) fetch by id
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT id, reminder_date, message FROM reminders WHERE id = ?" . ($est_col ? " AND ($est_col = ? OR ? IS NULL)" : ""));
    if ($est_col) {
        if (strpos($est_col, 'id') !== false || strpos($est_col, 'sid') !== false) {
            $estVal = $est_sid ? intval($est_sid) : null;
            $stmt->bind_param('iss', $id, $estVal, $estVal);
        } else {
            $estVal = $est_name ?? null;
            $stmt->bind_param('iss', $id, $estVal, $estVal);
        }
    } else {
        $stmt->bind_param('i', $id);
    }
    $stmt->execute();
    $res = $stmt->get_result();
    $out = [];
    while ($r = $res->fetch_assoc()) $out[] = $r;
    echo json_encode($out);
    exit;
}

// 2) fetch by date (all reminders for a date)
if (isset($_GET['date'])) {
    $date = $conn->real_escape_string($_GET['date']);
    if ($est_col) {
        $sql = "SELECT id, reminder_date, message FROM reminders WHERE reminder_date = ? AND $est_col = ?";
        $stmt = $conn->prepare($sql);
        if (strpos($est_col, 'id') !== false || strpos($est_col, 'sid') !== false) {
            $val = $est_sid ? intval($est_sid) : 0;
            $stmt->bind_param('si', $date, $val);
        } else {
            $val = $est_name ?? $est_sid;
            $stmt->bind_param('ss', $date, $val);
        }
    } else {
        $stmt = $conn->prepare("SELECT id, reminder_date, message FROM reminders WHERE reminder_date = ?");
        $stmt->bind_param('s', $date);
    }
    $stmt->execute();
    $res = $stmt->get_result();
    $out = [];
    while ($r = $res->fetch_assoc()) $out[] = $r;
    echo json_encode($out);
    exit;
}

// 3) fetch by month/year => return keyed by date
if (isset($_GET['year']) && isset($_GET['month'])) {
    $y = intval($_GET['year']);
    $m = intval($_GET['month']);
    $start = sprintf('%04d-%02d-01', $y, $m);
    $end = date('Y-m-d', strtotime("$start +1 month -1 day"));
    if ($est_col) {
        $sql = "SELECT id, reminder_date, message FROM reminders WHERE reminder_date BETWEEN ? AND ? AND $est_col = ? ORDER BY reminder_date";
        $stmt = $conn->prepare($sql);
        if (strpos($est_col, 'id') !== false || strpos($est_col, 'sid') !== false) {
            $val = $est_sid ? intval($est_sid) : 0;
            $stmt->bind_param('sss', $start, $end, $val);
        } else {
            $val = $est_name ?? $est_sid;
            $stmt->bind_param('sss', $start, $end, $val);
        }
    } else {
        $stmt = $conn->prepare("SELECT id, reminder_date, message FROM reminders WHERE reminder_date BETWEEN ? AND ? ORDER BY reminder_date");
        $stmt->bind_param('ss', $start, $end);
    }
    $stmt->execute();
    $res = $stmt->get_result();
    $out = [];
    while ($r = $res->fetch_assoc()) {
        $d = $r['reminder_date'];
        if (!isset($out[$d])) $out[$d] = [];
        $out[$d][] = $r;
    }
    echo json_encode($out);
    exit;
}

echo json_encode([]);
