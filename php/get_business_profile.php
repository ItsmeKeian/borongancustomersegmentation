<?php
require_once __DIR__ . '/require_login.php';
require_role('Establishment');
require_once __DIR__ . '/dbconnect.php'; // ✅ Include DB connection

header('Content-Type: application/json');

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo json_encode(["status" => 0, "message" => "No logged-in user found"]);
    exit;
}

try {
    // ✅ Use PDO from dbconnect.php
    $stmt = $pdo->prepare("
        SELECT 
            establishment_sid,
            business_name,
            business_type,
            owners_name,
            email,
            contact,
            address
        FROM establishment
        WHERE establishment_sid = :id
        LIMIT 1
    ");
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        echo json_encode(["status" => 1, "data" => $data]);
    } else {
        echo json_encode(["status" => 0, "message" => "No record found"]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => "Database error: " . $e->getMessage()]);
}
