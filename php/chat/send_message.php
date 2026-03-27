<?php
session_start();
require_once __DIR__ . '/../dbconnect.php';
header('Content-Type: application/json');

// Ensure logged in as establishment
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    echo json_encode(["status" => 0, "msg" => "Unauthorized"]);
    exit;
}

$estId = $_SESSION['user_id'];  // establishment_sid
if (!$estId) {
    echo json_encode(["status" => 0, "msg" => "Missing establishment ID"]);
    exit;
}

// Read message
$data = json_decode(file_get_contents("php://input"), true);
$message = trim($data['message'] ?? "");

if ($message === "") {
    echo json_encode(["status" => 0, "msg" => "Empty message"]);
    exit;
}

// 1️⃣ Save USER message into DB
$save = $conn->prepare("
    INSERT INTO messages (establishment_id, sender_type, sender_id, message, is_read)
    VALUES (?, 'user', ?, ?, 0)
");
$save->bind_param("iis", $estId, $estId, $message);
$save->execute();
$save->close();

// 2️⃣ FAQ Responses
$FAQ = [
    
    "dashboard" => "The dashboard shows your statistics.",
    "segment" => "Create segments in the Filter Segment page.",
    "customers" => "Go to Customers to view your customer list.",
    "campaign" => "Use the Campaigns page to send SMS/Email."
];

$lower = strtolower($message);
foreach ($FAQ as $key => $reply) {
    if (str_contains($lower, $key)) {
        echo json_encode([
            "status" => 1,
            "reply"  => $reply,
            "from_bot" => true
        ]);
        exit;
    }
}

// 3️⃣ Check last bot reply timestamp
$check = $conn->prepare("
    SELECT last_bot_reply 
    FROM establishment 
    WHERE establishment_sid = ?
");
$check->bind_param("i", $estId);
$check->execute();
$row = $check->get_result()->fetch_assoc();
$check->close();

$lastBot = $row['last_bot_reply'] ?? null;

// Check 5-hour cooldown
if (!empty($lastBot)) {
    $hoursPassed = (time() - strtotime($lastBot)) / 3600;
    if ($hoursPassed < 5) {
        echo json_encode([
            "status" => 1,
            "reply" => null,
            "from_bot" => false
        ]);
        exit;
    }
}

// 4️⃣ Check if admin replied (fix)
$checkAdmin = $conn->prepare("
    SELECT COUNT(*) AS cnt
    FROM messages
    WHERE establishment_id = ?
      AND sender_type = 'admin'
");
$checkAdmin->bind_param("i", $estId);
$checkAdmin->execute();
$adm = $checkAdmin->get_result()->fetch_assoc()['cnt'];
$checkAdmin->close();

// If admin replied → bot must STOP
if ($adm > 0) {
    echo json_encode([
        "status" => 1,
        "reply" => null,
        "from_bot" => false
    ]);
    exit;
}

// 5️⃣ Send bot auto-reply once every 5 hours
$botReply = "I couldn't find a direct answer. Your question has been forwarded to admin.";

// Update cooldown timestamp
$upd = $conn->prepare("
    UPDATE establishment 
    SET last_bot_reply = NOW()
    WHERE establishment_sid = ?
");
$upd->bind_param("i", $estId);
$upd->execute();
$upd->close();

// Return bot reply (DO NOT SAVE TO DB)
echo json_encode([
    "status" => 1,
    "reply" => $botReply,
    "from_bot" => true
]);
exit;

?>
