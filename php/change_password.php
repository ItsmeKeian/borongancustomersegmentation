<?php
require_once __DIR__ . '/require_login.php';
require_role('Establishment');
require_once __DIR__ . '/dbconnect.php';

header('Content-Type: application/json');

// Ensure logged in
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    echo json_encode(["status" => 0, "message" => "Unauthorized"]);
    exit;
}

// Validate required fields
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';

if (empty($current_password) || empty($new_password)) {
    echo json_encode(["status" => 0, "message" => "All fields are required"]);
    exit;
}

try {
    // Step 1: Fetch current hashed password
    $stmt = $pdo->prepare("SELECT password FROM establishment WHERE establishment_sid = :id");
    $stmt->execute([':id' => $userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(["status" => 0, "message" => "User not found"]);
        exit;
    }

    $hashedPassword = $row['password'];

    // Step 2: Verify current password
    if (!password_verify($current_password, $hashedPassword)) {
        echo json_encode(["status" => 0, "message" => "Current password is incorrect"]);
        exit;
    }

    // Step 3: Hash new password
    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Step 4: Update password in DB
    $update = $pdo->prepare("UPDATE establishment SET password = :password WHERE establishment_sid = :id");
    $update->execute([
        ':password' => $new_hashed_password,
        ':id' => $userId
    ]);

    echo json_encode(["status" => 1, "message" => "Password updated successfully"]);
    exit;

} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => "Database error: " . $e->getMessage()]);
    exit;
}
