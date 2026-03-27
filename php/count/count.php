<?php
session_start();
include('../dbconnect.php');

// Check if establishment is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    echo json_encode(["status" => 0, "message" => "Unauthorized"]);
    exit();
}

$business_name = $_SESSION['business_name'];

try {
    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // --- Count Total Segments ---
    $qry1 = "SELECT COUNT(DISTINCT name) AS total_segments 
             FROM segments 
             WHERE establishment = :bname";
    $stmt1 = $dbh->prepare($qry1);
    $stmt1->bindParam(":bname", $business_name, PDO::PARAM_STR);
    $stmt1->execute();     
    $segments = $stmt1->fetch(PDO::FETCH_ASSOC)['total_segments'] ?? 0;

    // --- Count Active Campaigns ---
    $qry2 = "SELECT COUNT(*) AS active_campaigns 
             FROM campaigns 
             WHERE establishment = :bname AND status = 'Sent'";
    $stmt2 = $dbh->prepare($qry2);
    $stmt2->bindParam(":bname", $business_name, PDO::PARAM_STR);
    $stmt2->execute();
    $campaigns = $stmt2->fetch(PDO::FETCH_ASSOC)['active_campaigns'] ?? 0;

    // --- Count Orders ---
    $qry3 = "SELECT COUNT(DISTINCT full_name) AS total_customer 
             FROM customer 
             WHERE establishment = :bname";
    $stmt3 = $dbh->prepare($qry3);
    $stmt3->bindParam(":bname", $business_name, PDO::PARAM_STR);
    $stmt3->execute();
    $customer = $stmt3->fetch(PDO::FETCH_ASSOC)['total_customer'] ?? 0;

    // --- Return all counts in one JSON ---
    echo json_encode([
        "status" => 1,
        "segments" => $segments,
        "campaigns" => $campaigns,
        "customer" => $customer
    ]);

} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => "Error: " . $e->getMessage()]);
}

$dbh = null;
?>
