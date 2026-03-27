<?php 
session_start();
header('Content-Type: application/json');

include('../dbconnect.php');

// Get logged-in establishment from session
// Check if establishment is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    echo json_encode(["status" => 0, "message" => "Unauthorized"]);
    exit();
}

$establishment = $_SESSION['business_name']; // Assign business_name to $establishment
try {
    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch campaigns
    $stmt = $dbh->prepare("
        SELECT 
            campaign_sid,
            campaign_name,
            target_segment,
            channel,
            message,
            schedule_time,
            status,
            sent_count,
            created_at
        FROM campaigns 
        WHERE establishment = :establishment 
        ORDER BY schedule_time DESC
    ");
    $stmt->bindParam(':establishment', $establishment, PDO::PARAM_STR);
    $stmt->execute();

    $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($campaigns) {
        // Format dates before sending JSON
        foreach ($campaigns as &$row) {
            if (!empty($row['schedule_time'])) {
                $row['schedule_time'] = (new DateTime($row['schedule_time']))->format('Y-m-d H:i');
            }
            if (!empty($row['created_at'])) {
                $row['created_at'] = (new DateTime($row['created_at']))->format('Y-m-d H:i');
            }
        }

        echo json_encode([
            'status' => 1,
            'data' => $campaigns
        ]);
    } else {
        echo json_encode([
            'status' => 0,
            'message' => 'No records found'
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        'status' => 0,
        'message' => 'Database Error: ' . $e->getMessage()
    ]);
}

$dbh = null;
?>
