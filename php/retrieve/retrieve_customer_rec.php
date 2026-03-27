<?php
session_start();
include('../dbconnect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    echo json_encode(["status" => 0, "message" => "Unauthorized"]);
    exit();
}

$business_name = $_SESSION['business_name'];
$limit = isset($_POST['limit']) ? intval($_POST['limit']) : 10;
$page  = isset($_POST['page']) ? intval($_POST['page']) : 1;
$offset = ($page - 1) * $limit;
$search = isset($_POST['search']) ? trim($_POST['search']) : "";

try {
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // search filter
    $where = " WHERE establishment = :bname ";
    if ($search !== "") {
        $where .= " AND full_name LIKE :search ";
    }

    // Count total records
    $countStmt = $dbh->prepare("SELECT COUNT(DISTINCT full_name) FROM customer $where");
    $countStmt->bindParam(':bname', $business_name, PDO::PARAM_STR);
    if ($search !== "") {
        $like = "%$search%";
        $countStmt->bindParam(':search', $like, PDO::PARAM_STR);
    }
    $countStmt->execute();
    $totalRecords = (int)$countStmt->fetchColumn();

    // Main query
    $stmt = $dbh->prepare("
    SELECT
        customer_sid,
        full_name,
        age,
        gender,
        location,
        email,
        phone,
        segment,
        occupation,
        estimated_income,
        education
    FROM customer
    $where
    ORDER BY full_name ASC
    LIMIT :limit OFFSET :offset
");


    $stmt->bindParam(':bname', $business_name, PDO::PARAM_STR);
    if ($search !== "") {
        $stmt->bindParam(':search', $like, PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($clients !== false) {
        echo json_encode([
            "status" => 1,
            "total"  => $totalRecords,
            "data"   => $clients
        ]);
    } else {
        echo json_encode([
            "status" => 0,
            "total"  => $totalRecords,
            "message" => "No records found"
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        "status" => 0,
        "message" => "Database Error: " . $e->getMessage()
    ]);
}

$dbh = null;
?>
