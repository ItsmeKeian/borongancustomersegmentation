<?php
session_start();
include('../dbconnect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    echo json_encode(["status" => 0, "message" => "Unauthorized", "data" => []]);
    exit();
}

$business_name = $_SESSION['business_name'];

$limit  = isset($_POST['limit']) ? intval($_POST['limit']) : 10;
$page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
$offset = ($page - 1) * $limit;
$search = isset($_POST['search']) ? trim($_POST['search']) : "";

try {
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $where = " WHERE p.establishment = :bname ";
    if ($search !== "") {
        $where .= " AND p.full_name LIKE :search ";
    }

    // ✅ COUNT RECEIPTS
    $countStmt = $dbh->prepare("SELECT COUNT(*) FROM purchased p $where");
    $countStmt->bindParam(':bname', $business_name);
    if ($search !== "") {
        $like = "%$search%";
        $countStmt->bindParam(':search', $like);
    }
    $countStmt->execute();
    $totalRecords = $countStmt->fetchColumn();

    // ✅ MAIN QUERY
    $stmt = $dbh->prepare("
        SELECT 
            p.purchased_sid,
            p.full_name,
            p.date_purchase,
            p.total,
            GROUP_CONCAT(pi.item_name SEPARATOR ', ') AS items
        FROM purchased p
        LEFT JOIN purchase_items pi 
            ON p.purchased_sid = pi.purchase_id
        $where
        GROUP BY p.purchased_sid
        ORDER BY p.date_purchase DESC
        LIMIT :limit OFFSET :offset
    ");

    $stmt->bindParam(':bname', $business_name);
    if ($search !== "") {
        $stmt->bindParam(':search', $like);
    }

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => 1,
        "total"  => $totalRecords,
        "data"   => $data
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "status" => 0,
        "message" => $e->getMessage(),
        "data" => []
    ]);
}
