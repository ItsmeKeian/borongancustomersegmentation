<?php
require_once __DIR__ . '/dbconnect.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

try {
    // 1. Validate email
    if (!isset($_POST['email']) || empty($_POST['email'])) {
        echo json_encode(["status" => 0, "message" => "Email is required."]);
        exit;
    }

    $email = trim($_POST['email']);

    // 2. Check if email exists
    $stmt = $pdo->prepare("SELECT establishment_sid FROM establishment WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["status" => 0, "message" => "Email address not found."]);
        exit;
    }

    date_default_timezone_set('Asia/Manila'); // ✅ Fix timezone

        $token = bin2hex(random_bytes(32)); // raw token
        $hashedToken = hash('sha256', $token);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour')); // expires in 1 hour
        $createdAt = date('Y-m-d H:i:s');

        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO password_resets (email, token, expires_at, created_at)
            VALUES (:email, :token, :expires_at, :created_at)
        ");

        $stmt->execute([
            ':email'      => $email,
            ':token'      => $hashedToken,
            ':expires_at' => $expiresAt,
            ':created_at' => $createdAt
        ]);

// Send reset link with RAW token
$resetLink = "https://boronganinsights.com/reset_password.php?token=" . $token;

    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 2; // debug logs
    $mail->Debugoutput = 'error_log';

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'gacilloskeian02@gmail.com'; // Gmail
    $mail->Password   = '';          // Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('gacilloskeian02@gmail.com', 'Borongan Customer Segmentation');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Password Reset Request';
    $mail->Body = "
        <p>We received a request to reset your password.</p>
        <p>Click the link below to reset your password:</p>
        <p><a href='$resetLink'>$resetLink</a></p>
        <p>This link will expire in 1 hour.</p>
    ";

    $mail->send();

    echo json_encode(["status" => 1, "message" => "A password reset link has been sent to your email."]);
    exit;

} catch (Exception $e) {
    // Log the exact error
    error_log("Forgot Password Error: " . $e->getMessage());

    echo json_encode([
        "status" => 0,
        "message" => "Mailer Error: " . $e->getMessage()
    ]);
    exit;
}
