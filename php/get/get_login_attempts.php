<?php
session_start();
require_once __DIR__ . '/../dbconnect.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // ✅ Only allow Admin
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
        echo json_encode(["status" => 0, "msg" => "Unauthorized"]);
        exit;
    }

    // ✅ Database connection
    $dbh = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // ✅ Get page & limit
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;

    $limit = 5;
    $offset = ($page - 1) * $limit;

    // ✅ Count total attempts
    $countStmt = $dbh->query("SELECT COUNT(*) FROM failed_logins");
    $total = $countStmt->fetchColumn();
    $totalPages = ceil($total / $limit);

    // ✅ Fetch paginated results (no double timezone conversion)
    $sql = "
        SELECT 
            username, 
            establishment_name, 
            attempts, 
            DATE_FORMAT(last_attempt, '%Y-%m-%d %H:%i:%s') AS last_attempt
        FROM failed_logins 
        ORDER BY last_attempt DESC 
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $rows = $stmt->fetchAll();

    echo json_encode([
        "status" => 1,
        "data" => $rows,
        "totalPages" => $totalPages,
        "currentPage" => $page
    ]);

} catch (PDOException $e) {
    echo json_encode(["status" => 0, "msg" => "Database error: " . $e->getMessage()]);
}
