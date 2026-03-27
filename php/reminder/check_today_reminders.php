<?php
include '../dbconnect.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Establishment') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$establishment_id = $_SESSION['business_name'];
$today = date("Y-m-d");

// 1️⃣ Get all reminders scheduled for today and not yet notified
$stmt = $conn->prepare("
    SELECT id, message 
    FROM reminders 
    WHERE reminder_date = ? 
      AND establishment_id = (SELECT establishment_sid FROM establishment WHERE business_name = ?)
      AND is_notified = 0
");
$stmt->bind_param("ss", $today, $establishment_id);
$stmt->execute();
$result = $stmt->get_result();

$countInserted = 0;

while ($row = $result->fetch_assoc()) {

    // 2️⃣ Insert notification for each reminder
    $msg = "Reminder today: " . $row['message'];
    $insert = $conn->prepare("
        INSERT INTO notifications (establishment, message, is_read)
        VALUES (?, ?, 0)
    ");
    $insert->bind_param("ss", $establishment_id, $msg);
    $insert->execute();

    // 3️⃣ Mark reminder as notified
    $upd = $conn->prepare("UPDATE reminders SET is_notified = 1 WHERE id = ?");
    $upd->bind_param("i", $row['id']);
    $upd->execute();

    $countInserted++;
}

echo json_encode([
    'success' => true,
    'notified' => $countInserted
]);
