<?php
require_once __DIR__ . '/require_login.php';
require_once __DIR__ . '/dbconnect.php';

header("Content-Type: application/json");

$establishment_id = $_SESSION['establishment_sid'] ?? null;

if (!$establishment_id) {
    echo json_encode(["status" => "error", "message" => "Not logged in."]);
    exit;
}

$date = $_POST['date'] ?? null;
$message = $_POST['message'] ?? null;

if (!$date || !$message) {
    echo json_encode(["status" => "error", "message" => "Missing fields"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO reminders (establishment_id, reminder_date, message) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $establishment_id, $date, $message);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error"]);
}
?>
