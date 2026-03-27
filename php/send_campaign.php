<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';
include('dbconnect.php');

try {
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kunin data from form
    $title = $_POST['title'] ?? '';
    $body  = $_POST['body'] ?? '';

    if (empty($title) || empty($body)) {
        echo json_encode(['status' => 0, 'message' => 'Please complete all fields']);
        exit;
    }

    // Save to campaign table
    $qry = "INSERT INTO campaign (title, body, created_at) VALUES (:title, :body, NOW())";
    $stmt = $dbh->prepare($qry);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':body', $body);

    if ($stmt->execute()) {
        // ====== SEND EMAILS IMMEDIATELY ======
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'gacilloskeian02@gmail.com';  // palitan ng Gmail mo
        $mail->Password   = '';     // 16-digit App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->setFrom('gacilloskeian02@gmail.com', 'TATA TEAS COFFEE SHOP');
        $mail->isHTML(true);
        $mail->Subject = $title;

        // Fetch customer emails
        $qry2 = "SELECT email FROM customer WHERE email IS NOT NULL AND email != ''";
        $stmt2 = $dbh->prepare($qry2);
        $stmt2->execute();
        $customers = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        foreach ($customers as $row) {
            $mail->clearAddresses();
            $mail->addAddress($row['email']);
            $mail->Body = "<h3>{$title}</h3><p>{$body}</p>";

            try {
                $mail->send();
            } catch (Exception $e) {
                error_log("Failed to send to {$row['email']}: " . $mail->ErrorInfo);
            }
        }

        echo json_encode(['status' => 1, 'message' => 'Campaign created and emails sent!']);
    } else {
        echo json_encode(['status' => 0, 'message' => 'Failed to create campaign']);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 0, 'message' => 'Error: '.$e->getMessage()]);
}
