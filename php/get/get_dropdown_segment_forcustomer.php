<?php
session_start();
include('../dbconnect.php');
header('Content-Type: application/json');

// Check if establishment is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    echo json_encode(["status" => 0, "message" => "Unauthorized"]);
    exit();
}

if (!isset($_SESSION['business_name'])) {
    echo json_encode(["status" => 0, "message" => "Business name not found in session"]);
    exit();
}

$establishment = $_SESSION['business_name'];

try {
    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ Filter segments by logged-in establishment
    $stmt = $dbh->prepare("SELECT DISTINCT name FROM segments WHERE establishment = :establishment ORDER BY name ASC");
    $stmt->bindParam(':establishment', $establishment);
    $stmt->execute();

    $segments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => 1, "segments" => $segments]);
} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => $e->getMessage()]);
}
