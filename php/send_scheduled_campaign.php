<?php
/**
 * Cronjob script to send scheduled campaigns (Email + SMS)
 * Run every 5 minutes
 */

require_once __DIR__ . '/dbconnect.php';
require_once __DIR__ . '/mailer.php';
require_once __DIR__ . '/sms_sender.php'; // your ClickSend integration

date_default_timezone_set("Asia/Manila");
$logFile = __DIR__ . "/cron_log.txt";

// Make sure log folder exists
if (!file_exists(dirname($logFile))) {
    mkdir(dirname($logFile), 0777, true);
}

function writeLog($message) {
    global $logFile;
    file_put_contents(
        $logFile,
        "[" . date("Y-m-d H:i:s") . "] " . $message . PHP_EOL,
        FILE_APPEND
    );
}

try {
    $dbh = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, 
        DB_USER, 
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $dbh->exec("SET time_zone = '+08:00'");

    writeLog("🚀 Cron script started");

    // ✅ Get scheduled campaigns ready to send
    $stmt = $dbh->prepare("
        SELECT * FROM campaigns 
        WHERE schedule_time <= NOW() 
          AND status = 'Scheduled'
    ");
    $stmt->execute();
    $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$campaigns) {
        writeLog("ℹ️ No campaigns to send.");
    }

    foreach ($campaigns as $campaign) {
        $campaignId    = $campaign['campaign_sid'];
        $segment       = $campaign['target_segment'];
        $message       = $campaign['message'];
        $establishment = $campaign['establishment'];
        $campaignName  = $campaign['campaign_name'];
        $channel       = strtolower($campaign['channel']);
        $sentCount     = 0;

        writeLog("📢 Processing campaign ID {$campaignId} [{$campaignName}] for segment {$segment} (Channel: {$channel})");

        // ✅ Fetch target customers
        if (strtolower($segment) === 'all') {
            $stmt2 = $dbh->prepare("
                SELECT email, phone FROM customer 
                WHERE (email IS NOT NULL OR phone IS NOT NULL) 
                  AND (email != '' OR phone != '') 
                  AND establishment = :establishment
            ");
            $stmt2->execute([':establishment' => $establishment]);
        } else {
            $stmt2 = $dbh->prepare("
                SELECT email, phone FROM customer 
                WHERE (email IS NOT NULL OR phone IS NOT NULL) 
                  AND (email != '' OR phone != '') 
                  AND LOWER(segment) = LOWER(:segment)
                  AND establishment = :establishment
            ");
            $stmt2->execute([
                ':segment' => $segment,
                ':establishment' => $establishment
            ]);
        }

        $customers = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        writeLog("👥 Found " . count($customers) . " customer(s)");

        // ✅ Prepare message with header
        $header = "Hello, this is {$establishment}.\n";
        $finalMessage = $header . $message;

        // ✅ Send email or SMS
        foreach ($customers as $cust) {
            if ($channel === 'email' && !empty($cust['email'])) {
                if (sendEmail($cust['email'], $campaignName, $message, $establishment)) {
                    $sentCount++;
                    writeLog("✅ Email sent to {$cust['email']}");
                } else {
                    writeLog("❌ Failed to send email to {$cust['email']}");
                }
            }

            if ($channel === 'sms' && !empty($cust['phone'])) {
                $phone = $cust['phone'];
                if (substr($phone, 0, 1) === '0') {
                    $phone = '+63' . substr($phone, 1);
                }

                if (sendSMS($phone, $finalMessage)) {
                    $sentCount++;
                    writeLog("✅ SMS sent to {$phone}");
                } else {
                    writeLog("❌ Failed to send SMS to {$phone}");
                }
            }
        }

        // ✅ Update campaign status and sent_count
        $update = $dbh->prepare("
            UPDATE campaigns 
            SET status = 'Sent', sent_count = :count 
            WHERE campaign_sid = :id
        ");
        $update->execute([
            ':count' => $sentCount,
            ':id'    => $campaignId
        ]);

        writeLog("📌 Campaign {$campaignId} finished. Sent to {$sentCount} customers.");
    }

    writeLog("🏁 Cron script finished");

} catch (Exception $e) {
    writeLog("❌ Error: " . $e->getMessage());
    echo "❌ Error: " . $e->getMessage();
}
?>
