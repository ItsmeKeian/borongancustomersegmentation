<?php
include '../dbconnect.php';
session_start();

try {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Establishment') {
        exit("Unauthorized");
    }

    $establishment = $_SESSION['business_name'] ?? null;
    if (!$establishment) {
        exit("No establishment found in session");
    }

    // Calculate 2 weeks ago
    $twoWeeksAgo = date('Y-m-d H:i:s', strtotime('-14 days'));

    // 1️⃣ Get all customers with last purchase before 2 weeks ago
    $stmt = $conn->prepare("
        SELECT c.customer_sid, c.full_name, MAX(p.date_purchase) AS last_purchase
        FROM purchased p
        JOIN (
            SELECT DISTINCT customer_sid, full_name
            FROM purchased
            WHERE establishment = ?
        ) c ON c.customer_sid = p.customer_sid
        WHERE p.establishment = ?
        GROUP BY c.customer_sid, c.full_name
        HAVING last_purchase < ?
    ");

    $stmt->bind_param("sss", $establishment, $establishment, $twoWeeksAgo);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $message = "Customer {$row['full_name']} has not purchased in 2 weeks.";

        // 2️⃣ Check if this notification already exists
        $check = $conn->prepare("SELECT id FROM notifications WHERE establishment = ? AND message = ? LIMIT 1");
        $check->bind_param("ss", $establishment, $message);
        $check->execute();
        $check->store_result();

        if ($check->num_rows === 0) {
            // Insert new notification
            $insert = $conn->prepare("INSERT INTO notifications (establishment, message) VALUES (?, ?)");
            $insert->bind_param("ss", $establishment, $message);
            $insert->execute();
            $insert->close();
        }

        $check->close();
    }

    echo "Inactive customer notifications generated.";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
