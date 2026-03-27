<?php
include('dbconnect.php');
header('Content-Type: application/json');

// ✅ Ensure we have a valid database connection
if ($pdo === null) {
    echo json_encode(["status" => 0, "message" => "Database connection error."]);
    exit;
}

// ✅ Validate incoming data
if (empty($_POST['token']) || empty($_POST['newPassword'])) {
    echo json_encode(["status" => 0, "message" => "All fields are required."]);
    exit;
}

$token = hash('sha256', $_POST['token']);
$newPassword = $_POST['newPassword'];

// ✅ Password strength validation
if (strlen($newPassword) < 8) {
    echo json_encode(["status" => 0, "message" => "Password must be at least 8 characters long."]);
    exit;
}

$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

try {
    // ✅ Verify token and fetch associated email
    $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = :token AND expires_at > NOW()");
    $stmt->execute([':token' => $token]);
    $resetData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resetData) {
        echo json_encode(["status" => 0, "message" => "Invalid or expired token."]);
        exit;
    }

    $email = $resetData['email'];

    // ✅ Update password in the `establishment` table
    $stmt = $pdo->prepare("UPDATE establishment SET password = :password WHERE email = :email");
    $updateSuccess = $stmt->execute([
        ':password' => $hashedPassword,
        ':email' => $email
    ]);

    if ($updateSuccess) {
        // ✅ Delete the token after use
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = :token");
        $stmt->execute([':token' => $token]);

        echo json_encode(["status" => 1, "message" => "Password has been successfully reset."]);
    } else {
        echo json_encode(["status" => 0, "message" => "Failed to update password. Please try again later."]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => "Database error: " . $e->getMessage()]);
}
exit;
?>
