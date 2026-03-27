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

    // Count unique customers (by full_name) for this establishment
    $qry = "SELECT COUNT(full_name) AS total_records 
            FROM customer 
            WHERE establishment = :bname";
    $stmt = $dbh->prepare($qry);
    $stmt->bindParam(":bname", $business_name, PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($data) {
        echo json_encode(["status" => 1, "unid" => $data['total_records']]);
    } else {
        echo json_encode(["status" => 0, "message" => "No records found"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => "Error: " . $e->getMessage()]);
}

$dbh = null;
?>
