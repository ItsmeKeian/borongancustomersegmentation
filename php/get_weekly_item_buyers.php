<?php
require_once __DIR__ . '/require_login.php';
require_role('Establishment');
require_once __DIR__ . '/dbconnect.php';

header("Content-Type: application/json");
date_default_timezone_set('Asia/Manila');

$establishment = $_SESSION['business_name'] ?? '';
$item = $_POST['item'] ?? '';

if ($establishment === '' || $item === '') {
    echo json_encode([]);
    exit;
}

$sql = "
    SELECT 
        c.full_name,
        c.age,
        c.gender,
        c.location,
        c.segment,
        pi.quantity,
        (pi.quantity * pi.price) AS total_spent,
        p.date_purchase
    FROM purchased p
    JOIN purchase_items pi ON pi.purchase_id = p.purchased_sid
    JOIN inventory i ON i.inventory_id = pi.inventory_id
    JOIN customer c ON c.customer_sid = p.customer_sid
    WHERE p.establishment = ?
      AND i.item_name = ?
      AND YEARWEEK(p.date_purchase, 1) = YEARWEEK(CURDATE(), 1)
    ORDER BY p.date_purchase DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $establishment, $item);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode($result->fetch_all(MYSQLI_ASSOC));
