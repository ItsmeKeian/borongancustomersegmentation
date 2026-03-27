<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../dbconnect.php';
require_once __DIR__ . '/../fetch_logs.php';
require_once __DIR__ . '/../mailer.php';
require_once __DIR__ . '/../sms_sender.php';

date_default_timezone_set("Asia/Manila");
ini_set('display_errors', 0);
error_reporting(E_ALL);

/*
|--------------------------------------------------------------------------
| HELPER: GET RECIPIENTS (AUTO + MANUAL SEGMENTS)
|--------------------------------------------------------------------------
*/
function getRecipients(PDO $dbh, string $segment, string $establishment, string $field): array
{
    $segment = strtoupper(trim($segment));

    switch ($segment) {

        case 'ALL':
            $sql = "SELECT $field FROM customer WHERE $field != '' AND establishment = ?";
            $params = [$establishment];
            break;

        case 'STUDENTS':
            $sql = "SELECT $field FROM customer
                    WHERE education IS NOT NULL AND education != ''
                      AND $field != '' AND establishment = ?";
            $params = [$establishment];
            break;

        case 'WORKING PROFESSIONALS':
            $sql = "SELECT $field FROM customer
                    WHERE occupation IS NOT NULL AND occupation != ''
                      AND $field != '' AND establishment = ?";
            $params = [$establishment];
            break;

        case 'KIDS / TEENS':
            $sql = "SELECT $field FROM customer
                    WHERE age < 18 AND $field != '' AND establishment = ?";
            $params = [$establishment];
            break;

        case 'YOUNG ADULTS':
            $sql = "SELECT $field FROM customer
                    WHERE age BETWEEN 18 AND 30 AND $field != '' AND establishment = ?";
            $params = [$establishment];
            break;

        case 'ADULTS':
            $sql = "SELECT $field FROM customer
                    WHERE age BETWEEN 31 AND 59 AND $field != '' AND establishment = ?";
            $params = [$establishment];
            break;

        case 'SENIORS':
            $sql = "SELECT $field FROM customer
                    WHERE age >= 60 AND $field != '' AND establishment = ?";
            $params = [$establishment];
            break;

        case 'RETIREE':
            $sql = "SELECT $field FROM customer
                    WHERE occupation LIKE '%retir%' AND $field != '' AND establishment = ?";
            $params = [$establishment];
            break;

        case 'LOYAL CUSTOMERS':
            $sql = "
                SELECT c.$field
                FROM customer c
                INNER JOIN purchased p
                    ON p.customer_sid = c.customer_sid
                   AND p.establishment = c.establishment
                WHERE c.establishment = ?
                GROUP BY c.customer_sid
                HAVING COUNT(p.purchased_sid) >= 1
            ";
            $params = [$establishment];
            break;

        default: // MANUAL SEGMENT
            $sql = "
                SELECT $field FROM customer
                WHERE LOWER(segment) = LOWER(?)
                  AND $field != ''
                  AND establishment = ?
            ";
            $params = [$segment, $establishment];
    }

    $stmt = $dbh->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*
|--------------------------------------------------------------------------
| SECURITY CHECK
|--------------------------------------------------------------------------
*/
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Establishment') {
    echo json_encode(["status" => 0, "message" => "Unauthorized"]);
    exit;
}

$establishment = $_SESSION['business_name'] ?? null;
$userId        = $_SESSION['user_id'] ?? null;

if (!$establishment) {
    echo json_encode(["status" => 0, "message" => "Invalid session"]);
    exit;
}

/*
|--------------------------------------------------------------------------
| INPUT VALIDATION
|--------------------------------------------------------------------------
*/
$campaignName   = trim($_POST['campaignName'] ?? '');
$targetSegment  = trim($_POST['targetSegment'] ?? '');
$channel        = strtolower(trim($_POST['channel'] ?? ''));
$messageContent = trim($_POST['messageContent'] ?? '');
$scheduleTime   = $_POST['scheduleTime'] ?: null;
$status         = strtolower(trim($_POST['status'] ?? ''));

if (!$campaignName || !$targetSegment || !$channel || !$messageContent || !$status) {
    echo json_encode(["status" => 0, "message" => "Please fill all required fields"]);
    exit;
}

try {
    /*
    |--------------------------------------------------------------------------
    | DB CONNECTION
    |--------------------------------------------------------------------------
    */
    $dbh = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    /*
    |--------------------------------------------------------------------------
    | SAVE CAMPAIGN
    |--------------------------------------------------------------------------
    */
    $stmt = $dbh->prepare("
        INSERT INTO campaigns (
            establishment, campaign_name, target_segment,
            channel, message, schedule_time, status, sent_count
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 0)
    ");

    $stmt->execute([
        $establishment,
        $campaignName,
        $targetSegment,
        $channel,
        $messageContent,
        $scheduleTime,
        $status
    ]);

    /*
    |--------------------------------------------------------------------------
    | LOG CAMPAIGN
    |--------------------------------------------------------------------------
    */
    if ($userId) {
        logAction($dbh, $userId, "Campaign Created", [
            "campaign_name"  => $campaignName,
            "target_segment" => $targetSegment,
            "channel"        => $channel,
            "status"         => $status
        ], $establishment);
    }

    /*
    |--------------------------------------------------------------------------
    | SEND CAMPAIGN
    |--------------------------------------------------------------------------
    */
    $sentCount = 0;

    if (in_array($status, ['sent', 'active'])) {

        // EMAIL
        if ($channel === 'email') {
            $recipients = getRecipients($dbh, $targetSegment, $establishment, 'email');

            foreach ($recipients as $row) {
                if (sendEmail($row['email'], $campaignName, $messageContent, $establishment)) {
                    $sentCount++;
                }
            }
        }

        // SMS
        if ($channel === 'sms') {
            $recipients = getRecipients($dbh, $targetSegment, $establishment, 'phone');

            foreach ($recipients as $row) {
                $phone = $row['phone'];

                if (substr($phone, 0, 1) === '0') {
                    $phone = '+63' . substr($phone, 1);
                }

                if (sendSMS($phone, $messageContent, $establishment)) {
                    $sentCount++;
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE SENT COUNT
    |--------------------------------------------------------------------------
    */
    $update = $dbh->prepare("
        UPDATE campaigns
        SET sent_count = ?
        WHERE campaign_name = ? AND establishment = ?
    ");

    $update->execute([$sentCount, $campaignName, $establishment]);

    /*
    |--------------------------------------------------------------------------
    | RESPONSE
    |--------------------------------------------------------------------------
    */
    echo json_encode([
        "status"  => 1,
        "message" => "Campaign processed successfully. Sent to {$sentCount} recipient(s)."
    ]);

} catch (Exception $e) {

    error_log("Campaign Error: " . $e->getMessage());

    echo json_encode([
        "status" => 0,
        "message" => "Server error occurred. Please try again."
    ]);
}
