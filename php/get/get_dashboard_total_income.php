<?php
header('Content-Type: application/json');
include_once __DIR__ . "/../dbconnect.php";
session_start();

// ✅ Check kung naka-login
if (!isset($_SESSION['business_name'])) {
    echo json_encode([
        "status" => 0,
        "message" => "Session expired. Please log in."
    ]);
    exit();
}

$establishment = $_SESSION['business_name'];

try {
    // ✅ Connect using PDO
    $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ Query to get total revenue
    $sql = "
        SELECT SUM(total) AS total_income 
        FROM purchased
        WHERE establishment = :establishment
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute(['establishment' => $establishment]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => 1,
        "total_income" => $result['total_income'] ?? 0
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "status" => 0,
        "error" => $e->getMessage()
    ]);
}
?>
