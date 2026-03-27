<?php
session_start();
include('../dbconnect.php');
header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    echo json_encode(["status" => 0, "message" => "Unauthorized"]);
    exit();
}

$establishment = $_SESSION['business_name'] ?? null;
if (!$establishment) {
    echo json_encode(["status" => 0, "message" => "Business name missing"]);
    exit();
}

try {
    $dbh = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    /* ============================
       1) MANUAL SEGMENTS
    ============================ */
    $stmt = $dbh->prepare("
        SELECT DISTINCT name 
        FROM segments 
        WHERE establishment = :establishment
        ORDER BY name ASC
    ");
    $stmt->bindParam(':establishment', $establishment);
    $stmt->execute();
    $manualSegments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* ============================
       2) AUTO SEGMENTS (STATIC)
    ============================ */
    $autoSegments = [
        ["name" => "LOYAL CUSTOMERS"],
        ["name" => "STUDENTS"],
        ["name" => "PROFESSIONALS"],
        ["name" => "KIDS / TEENS"],
        ["name" => "YOUNG ADULTS"],
        ["name" => "ADULTS"],
        ["name" => "SENIORS"]
        
    ];

    echo json_encode([
        "status" => 1,
        "segments" => array_merge($manualSegments, $autoSegments)
    ]);

} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => $e->getMessage()]);
}
