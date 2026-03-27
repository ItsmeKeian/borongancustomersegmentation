<?php
include '../dbconnect.php';
session_start();
header('Content-Type: application/json');

// ✅ Only Admin can view this data
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    echo json_encode(["status" => 0, "message" => "Unauthorized"]);
    exit();
}

try {
    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ Remove "WHERE establishment = ?" since Admin should see all establishments
    $stmt = $dbh->prepare("
        SELECT business_type, COUNT(*) as count
        FROM establishment
        GROUP BY business_type
    ");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);

} catch (Exception $e) {
    echo json_encode(["status" => 0, "message" => $e->getMessage()]);
}
