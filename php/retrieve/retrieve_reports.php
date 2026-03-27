<?php
session_start();
include('../dbconnect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    echo json_encode(["status" => 0, "message" => "Unauthorized"]);
    exit();
}

$business_name = $_SESSION['business_name'];
$limit  = isset($_POST['limit']) ? intval($_POST['limit']) : 10;
$page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
$offset = ($page - 1) * $limit;
$search = isset($_POST['search']) ? trim($_POST['search']) : "";

try {
    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $where = "WHERE p.establishment = :bname";
    if ($search !== "") {
        $where .= " AND (c.full_name LIKE :search OR pi.item_name LIKE :search)";
    }

    // ✅ COUNT
    $countQuery = "
        SELECT COUNT(*)
        FROM purchased p
        JOIN customer c ON p.customer_sid = c.customer_sid
        JOIN purchase_items pi ON p.purchased_sid = pi.purchase_id
        $where
    ";

    $countStmt = $dbh->prepare($countQuery);
    $countStmt->bindParam(':bname', $business_name);

    if ($search !== "") {
        $like = "%$search%";
        $countStmt->bindParam(':search', $like);
    }

    $countStmt->execute();
    $totalRecords = (int)$countStmt->fetchColumn();

    // ✅ MAIN DATA
    $query = "
        SELECT 
            c.full_name,
            c.age,
            c.gender,
            c.phone,
            c.email,
            c.location,
            pi.item_name AS item_purchase,
            pi.price AS item_price,
            pi.quantity,
            pi.subtotal AS total,
            p.date_purchase
        FROM purchased p
        JOIN customer c ON p.customer_sid = c.customer_sid
        JOIN purchase_items pi ON p.purchased_sid = pi.purchase_id
        $where
        ORDER BY p.date_purchase DESC
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':bname', $business_name);

    if ($search !== "") {
        $stmt->bindParam(':search', $like);
    }

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode([
        "status" => 1,
        "total"  => $totalRecords,
        "data"   => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "status" => 0,
        "message" => "Database Error: " . $e->getMessage()
    ]);
}
?>
