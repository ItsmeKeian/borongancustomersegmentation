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
    // DB connect
    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 📊 Count establishments registered per month (all establishments)
    $sql = "
        SELECT 
            DATE_FORMAT(date_time, '%b %Y') AS month, 
            COUNT(*) AS establishment_count
        FROM establishment
        GROUP BY YEAR(date_time), MONTH(date_time)
        ORDER BY YEAR(date_time), MONTH(date_time)
    ";

    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $months = [];
    $counts = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $months[] = $row['month'];   // e.g. "Sep 2025"
        $counts[] = (int) $row['establishment_count'];
    }

    if (empty($months)) {
        echo json_encode([
            "status" => 0,
            "message" => "No establishment growth data found"
        ]);
    } else {
        echo json_encode([
            "status" => 1,
            "months" => $months,
            "counts" => $counts
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "status" => 0,
        "message" => "Database error: " . $e->getMessage()
    ]);
}
