<?php
include('../dbconnect.php');
include_once '../fetch_logs.php';
session_start();

// ✅ Allow only establishments
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

$establishment = $_SESSION['business_name'] ?? null;
$userId = $_SESSION['user_id'] ?? null;
if (!$establishment) {
    echo json_encode(["success" => false, "message" => "No establishment found in session"]);
    exit();
}

// ✅ Read JSON data from AJAX
$input = json_decode(file_get_contents("php://input"), true);

$segment_name = trim($input['segment_name'] ?? '');
$age_min      = $input['age_min'] ?? null;
$age_max      = $input['age_max'] ?? null;
$description  = $input['description'] ?? '';


// ✅ Set timezone to Asia/Manila
date_default_timezone_set("Asia/Manila");
$currentTime = date("Y-m-d H:i:s");

try {
    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ Insert new segment with PH time
    $qry = "INSERT INTO segments 
            (name, establishment, age_min, age_max, description, created_at)
            VALUES 
            (:name, :establishment, :age_min, :age_max, :description, :created_at)";

    $stmt = $dbh->prepare($qry);
    $stmt->bindParam(":name", $segment_name);
    $stmt->bindParam(":establishment", $establishment);
    $stmt->bindParam(":age_min", $age_min);
    $stmt->bindParam(":age_max", $age_max);
    $stmt->bindParam(":description", $description);
    
    $stmt->bindParam(":created_at", $currentTime);

    if ($stmt->execute()) {
        // Log action
        if ($userId) {
            $details = "Created new segment: ({$segment_name})";
            logAction($dbh, $userId, 'Create Segment', $details);
        }

        echo json_encode(["success" => true, "message" => "Segment created successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to create segment"]);
    }

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
