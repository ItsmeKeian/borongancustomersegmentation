<?php
session_start();
require_once __DIR__ . '/require_login.php';
require_role('Establishment');
require_once __DIR__ . '/dbconnect.php';
include_once 'fetch_logs.php';

header('Content-Type: application/json');

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo json_encode(["status" => 0, "message" => "No logged-in user found"]);
    exit;
}

// Validate required fields
$fields = ['business_name', 'business_type', 'owners_name', 'email', 'contact', 'address'];
foreach ($fields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(["status" => 0, "message" => "Missing required field: $field"]);
        exit;
    }
}

$business_name = $_POST['business_name'];

try {
    // ✅ Update using $pdo
    $stmt = $pdo->prepare("
        UPDATE establishment 
        SET business_name = :business_name,
            business_type = :business_type,
            owners_name = :owners_name,
            email = :email,
            contact = :contact,
            address = :address
        WHERE establishment_sid = :id
    ");

    $stmt->execute([
        ':business_name' => $business_name,
        ':business_type' => $_POST['business_type'],
        ':owners_name'   => $_POST['owners_name'],
        ':email'         => $_POST['email'],
        ':contact'       => $_POST['contact'],
        ':address'       => $_POST['address'],
        ':id'            => $userId
    ]);

    // ✅ Log action using $pdo
    logAction(
        $pdo,
        $userId,
        'Update Profile',
        "Updated profile record: ({$business_name})"
    );

    echo json_encode(["status" => 1, "message" => "Record updated successfully"]);

} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => "Database error: " . $e->getMessage()]);
}
?>
