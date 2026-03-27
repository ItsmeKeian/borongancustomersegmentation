<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

/**
 * Send email using PHPMailer
 *
 * @param string $to Recipient email
 * @param string $subject Email subject
 * @param string $body Message body
 * @param string $fromName Business/Store name
 * @return array ['success' => bool, 'error' => string|null]
 */
function sendEmail($to, $subject, $body, $fromName = "System") {
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = '';
        $mail->Password   = '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender
        $mail->setFrom($mail->Username, $fromName);
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "
            <div style='font-family: Arial, sans-serif;'>
                <h3>{$subject}</h3>
                <p>" . nl2br(htmlspecialchars($body)) . "</p>
            </div>
        ";

        $mail->send();

        return [
            "success" => true,
            "email"   => $to,
            "subject" => $subject
        ];

    } catch (Exception $e) {

        error_log("sendEmail error ({$to}): " . $mail->ErrorInfo);

        return [
            "success" => false,
            "email"   => $to,
            "subject" => $subject,
            "error"   => $mail->ErrorInfo
        ];
    }
}

