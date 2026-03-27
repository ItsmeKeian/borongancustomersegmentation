<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../dbconnect.php';
require_once __DIR__ . '/../mailer.php';

// ---------------------------------
// SECURITY CHECK
// ---------------------------------
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    echo json_encode([
        "status" => 0,
        "message" => "Unauthorized"
    ]);
    exit();
}

$userId        = $_SESSION['user_id'] ?? null;
$establishment = $_SESSION['business_name'] ?? 'System';
$ipAddress     = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

// ---------------------------------
// INPUTS
// ---------------------------------
$subject   = trim($_POST['subject'] ?? '');
$message   = trim($_POST['message'] ?? '');
$customers = $_POST['customers'] ?? [];

if ($subject === '' || $message === '' || empty($customers)) {
    echo json_encode([
        "status" => 0,
        "message" => "Missing subject, message, or selected customers."
    ]);
    exit();
}

// ---------------------------------
// FETCH CUSTOMER EMAILS + NAMES
// ---------------------------------
$placeholders = implode(',', array_fill(0, count($customers), '?'));

$sql = "
    SELECT customer_sid, full_name, email
    FROM customer
    WHERE customer_sid IN ($placeholders)
      AND email IS NOT NULL
      AND email != ''
";

$stmt = $conn->prepare($sql);
$types = str_repeat('i', count($customers));
$stmt->bind_param($types, ...$customers);
$stmt->execute();

$result = $stmt->get_result();

// ---------------------------------
// SEND EMAILS + PREPARE LOG DATA
// ---------------------------------
$sentCount     = 0;
$logCustomers  = [];

while ($row = $result->fetch_assoc()) {

    if (sendEmail($row['email'], $subject, $message, $establishment)) {
        $sentCount++;

        // ONLY what we want to display in logs
        $logCustomers[] = [
            "full_name" => $row['full_name']
        ];
    }
}
$stmt->close();

// ---------------------------------
// SAVE SYSTEM LOG (MATCHES VIEW MODAL)
// ---------------------------------
$logAction  = "Send Bulk Email";
$logDetails = json_encode([
    "subject"    => $subject,
    "message"    => $message,
    "sent_count" => $sentCount,
    "customers"  => $logCustomers
], JSON_UNESCAPED_UNICODE);

$logStmt = $conn->prepare("
    INSERT INTO system_logs
        (user_id, establishment_name, action, details, ip_address)
    VALUES
        (?, ?, ?, ?, ?)
");

$logStmt->bind_param(
    "issss",
    $userId,
    $establishment,
    $logAction,
    $logDetails,
    $ipAddress
);

$logStmt->execute();
$logStmt->close();

// ---------------------------------
// RESPONSE
// ---------------------------------
echo json_encode([
    "status" => 1,
    "sent"   => $sentCount,
    "message"=> "Bulk email successfully sent."
]);
