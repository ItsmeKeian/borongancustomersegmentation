<?php
session_start();
require_once '../dbconnect.php';

header('Content-Type: application/json');

// ✅ Validate session
$establishment = $_SESSION['business_name'] ?? '';
if ($establishment === '') {
    echo json_encode([]);
    exit;
}

// ✅ Asia/Manila timezone for date accuracy
date_default_timezone_set('Asia/Manila');

$sql = "
    SELECT 
        i.inventory_id,
        i.item_name,
        i.price,
        i.quantity,
        i.status,

        -- yesterday stock (nullable)
        ys.quantity AS yesterday_quantity

    FROM inventory i
    LEFT JOIN inventory_daily_stock ys
        ON ys.inventory_id = i.inventory_id
        AND ys.stock_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)

    WHERE i.establishment = ?
    ORDER BY i.item_name ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $establishment);
$stmt->execute();

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {

    // Optional: normalize null yesterday stock
    $row['yesterday_quantity'] = $row['yesterday_quantity'] !== null
        ? (int)$row['yesterday_quantity']
        : null;

    $data[] = $row;
}

echo json_encode($data);
