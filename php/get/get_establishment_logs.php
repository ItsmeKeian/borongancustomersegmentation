<?php
require_once __DIR__ . '/../dbconnect.php';
require_once __DIR__ . '/../require_login.php';
require_role('Establishment');

header('Content-Type: application/json; charset=utf-8');

$establishment = $_SESSION['business_name'] ?? null;
$establishmentId = $_SESSION['user_id'] ?? null;

$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;
$search = $_POST['search'] ?? '';

$offset = ($page - 1) * $limit;

try {

    // COUNT total logs for pagination
    $countSql = "
        SELECT COUNT(*) 
        FROM system_logs 
        WHERE user_id = ?
        AND (
            action LIKE ? 
            OR details LIKE ?
        )
    ";

    $countStmt = $pdo->prepare($countSql);
    $searchLike = "%$search%";
    $countStmt->execute([$establishmentId, $searchLike, $searchLike]);
    $totalLogs = (int)$countStmt->fetchColumn();


    // FETCH logs with pagination
    $sql = "
        SELECT 
            systemlog_sid,
            DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s') AS created_at,
            action,
            details,
            ip_address
        FROM system_logs
        WHERE user_id = ?
        AND (
            action LIKE ? 
            OR details LIKE ?
        )
        ORDER BY created_at DESC
        LIMIT ? OFFSET ?
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $establishmentId, PDO::PARAM_INT);
    $stmt->bindValue(2, $searchLike);
    $stmt->bindValue(3, $searchLike);
    $stmt->bindValue(4, $limit, PDO::PARAM_INT);
    $stmt->bindValue(5, $offset, PDO::PARAM_INT);
    $stmt->execute();

    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "logs" => $logs,
        "total" => $totalLogs,   // THIS FIXES YOUR "undefined"
        "status" => 1
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => 0,
        "logs" => [],
        "total" => 0,
        "error" => $e->getMessage()
    ]);
}
