<?php
include '../dbconnect.php';
session_start();
header('Content-Type: application/json');

try {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
        echo json_encode(["success" => false, "message" => "Unauthorized"]);
        exit();
    }

    $establishment = $_SESSION['business_name'] ?? null;
    if (!$establishment) {
        echo json_encode(["success" => false, "message" => "No establishment found"]);
        exit();
    }

    // Get all notifications for this establishment
    $stmt = $conn->prepare("
        SELECT id, message, is_read, date_created
        FROM notifications
        WHERE establishment = ?
        ORDER BY date_created DESC
        LIMIT 50
    ");
    $stmt->bind_param("s", $establishment);
    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];
    $unreadCount = 0;

    while ($row = $result->fetch_assoc()) {
        if ($row['is_read'] == 0) $unreadCount++;
        $notifications[] = $row;
    }

    echo json_encode([
        "success" => true,
        "notifications" => $notifications,
        "unreadCount" => $unreadCount
    ]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
