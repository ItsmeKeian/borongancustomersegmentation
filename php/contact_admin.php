<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/mailer.php';
date_default_timezone_set('Asia/Manila');

/* =====================================
 * RATE LIMIT
 * ===================================== */
if (!empty($_SESSION['support_sent']) && time() - $_SESSION['support_sent'] < 30) {
    echo json_encode([
        'status'  => 0,
        'message' => 'Please wait before sending another request.'
    ]);
    exit;
}

/* =====================================
 * INPUT VALIDATION
 * ===================================== */
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($email === '' || $subject === '' || $message === '') {
    echo json_encode([
        'status'  => 0,
        'message' => 'Please complete all required fields.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status'  => 0,
        'message' => 'Invalid email address.'
    ]);
    exit;
}

if (strlen($subject) > 100) {
    echo json_encode([
        'status'  => 0,
        'message' => 'Subject is too long.'
    ]);
    exit;
}

/* =====================================
 * EMAIL CONTENT
 * ===================================== */
$adminEmail = 'dawnbringerriven02@gmail.com';

$emailSubject = "System Support Request: {$subject}";

$emailBody = "
From Email: {$email}
Phone Number: {$phone}

Subject:
{$subject}

Message:
{$message}

-----------------------
IP Address: {$_SERVER['REMOTE_ADDR']}
Submitted At: " . date("Y-m-d H:i:s") . "
";

/* =====================================
 * SEND EMAIL
 * ===================================== */
$result = sendEmail(
    $adminEmail,
    $emailSubject,
    $emailBody,
    'Borongan Customer Segmentation'
);

if ($result['success']) {

    $_SESSION['support_sent'] = time();

    echo json_encode([
        'status'  => 1,
        'message' => 'Your message has been sent to the system administrator.'
    ]);

} else {

    echo json_encode([
        'status'  => 0,
        'message' => 'Failed to send message. Please try again later.'
    ]);
}
