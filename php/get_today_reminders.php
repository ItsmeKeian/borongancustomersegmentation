<?php
require_once __DIR__ . '/require_login.php';
require_once __DIR__ . '/dbconnect.php';

header("Content-Type: application/json");

$establishment_id = $_SESSION['establishment_sid'];
$today = date("Y-m-d");

$sql = "SELECT id, message FROM reminders
        WHERE establishment_id = ?
        AND reminder_date = ?
        AND is_notified = 0";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $establishment_id, $today);
$stmt->execute();
$res = $stmt->get_result();

$reminders = [];
while ($row = $res->fetch_assoc()) {
    $reminders[] = $row;
}

echo json_encode($reminders);

// mark as notified
$conn->query("UPDATE reminders SET is_notified = 1 
              WHERE reminder_date = '$today' AND establishment_id = $establishment_id");
