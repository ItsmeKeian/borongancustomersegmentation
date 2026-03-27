<?php 
session_start();
include('../dbconnect.php');
include_once '../fetch_logs.php';
header('Content-Type: application/json');

try {
    if (!isset($_POST['id'])) {
        echo json_encode(["status" => 0, "message" => "No ID provided"]);
        exit;
    }

    $id = intval($_POST['id']);

    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Get full campaign details BEFORE deleting
    $getCampaign = $dbh->prepare("
        SELECT campaign_name, target_segment, channel, message, status, schedule_time 
        FROM campaigns 
        WHERE campaign_sid = :id
    ");
    $getCampaign->bindParam(":id", $id, PDO::PARAM_INT);
    $getCampaign->execute();
    $campaign = $getCampaign->fetch(PDO::FETCH_ASSOC);

    if (!$campaign) {
        echo json_encode(["status" => 0, "message" => "Campaign not found"]);
        exit;
    }

    // 2. Perform deletion
    $stmt = $dbh->prepare("DELETE FROM campaigns WHERE campaign_sid = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {

        // 3. Create FULL LOG DETAILS (JSON)
        if (isset($_SESSION['user_id'])) {

            $logDetails = [
                "campaign_name"   => $campaign["campaign_name"],
                "target_segment"  => $campaign["target_segment"],
                "channel"         => $campaign["channel"],
                "message_preview" => mb_strimwidth($campaign["message"], 0, 60, "..."),
                "status"          => $campaign["status"],
                "schedule_time"   => $campaign["schedule_time"],
                "deleted_at"      => date("Y-m-d H:i:s")
            ];

            logAction(
                $dbh,
                $_SESSION['user_id'],
                'Delete Campaign',
                $logDetails,
                $_SESSION['business_name'] ?? null
            );
        }

        echo json_encode([
            "status" => 1, 
            "message" => "Campaign '{$campaign['campaign_name']}' deleted successfully"
        ]);

    } else {
        echo json_encode(["status" => 0, "message" => "No record found with that ID"]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => $e->getMessage()]);
}
?>
