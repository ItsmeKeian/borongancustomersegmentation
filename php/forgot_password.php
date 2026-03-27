<?php
require_once __DIR__ . '/dbconnect.php';
header('Content-Type: application/json');

$email = $_POST['email'] ?? '';

if (empty($email)) {
    echo json_encode(["status" => 0, "message" => "Email is required"]);
    exit;
}

try {
    // ✅ Check if email exists
    $stmt = $pdo->prepare("SELECT establishment_sid FROM establishment WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["status" => 0, "message" => "Email not found"]);
        exit;
    }

    // ✅ Generate reset token
    $token = bin2hex(random_bytes(32));
    $hashedToken = hash('sha256', $token);
    $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // ✅ Store token
    $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)")
        ->execute([
            ':email' => $email,
            ':token' => $hashedToken,
            ':expires_at' => $expires_at
        ]);

    // ✅ Create reset link
    $resetLink = "https://yourdomain.com/reset_password.php?token=$token";

    // 🚀 TODO: Send $resetLink via email to the user here
    // You can use PHPMailer or mail()

    echo json_encode([
        "status" => 1,
        "message" => "A password reset link has been sent to your email.",
        "debug_link" => $resetLink // For testing only, remove in production
    ]);

} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => "Database error: " . $e->getMessage()]);
    exit;
}
?>
