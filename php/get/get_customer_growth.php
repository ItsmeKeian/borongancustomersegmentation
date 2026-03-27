<?php
include '../dbconnect.php';
session_start();
header('Content-Type: application/json');

try {
    // Check establishment in session
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
        echo json_encode(["success" => false, "message" => "Unauthorized"]);
        exit();
    }

    $establishment = $_SESSION['business_name'] ?? null;
    if (!$establishment) {
        echo json_encode(["success" => false, "message" => "No establishment found in session"]);
        exit();
    }

    // DB connect
    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query grouped by month for this establishment
    $sql = "
        SELECT 
            DATE_FORMAT(created_at, '%b') AS month, 
            COUNT(*) AS customer_count
        FROM customer
        WHERE establishment = :establishment
        GROUP BY YEAR(created_at), MONTH(created_at)
        ORDER BY YEAR(created_at), MONTH(created_at)
    ";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":establishment", $establishment, PDO::PARAM_STR);
    $stmt->execute();

    $months = [];
    $counts = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $months[] = $row['month'];
        $counts[] = (int) $row['customer_count'];
    }

    if (empty($months)) {
        echo json_encode([
            "status" => 0,
            "message" => "No customer growth data found for establishment: $establishment"
        ]);
    } else {
        echo json_encode([
            "status" => 1,
            "months" => $months,
            "counts" => $counts,
            "establishment" => $establishment // debug output
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "status" => 0,
        "message" => "Database error: " . $e->getMessage()
    ]);
}
