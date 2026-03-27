<?php
require_once __DIR__ . '/require_login.php';
require_role('Establishment');
require_once __DIR__ . '/dbconnect.php';

header("Content-Type: application/json");

$establishment = $_SESSION['business_name'] ?? '';
$month = $_POST['month'] ?? '';

if ($establishment == '' || $month == '') {
    echo json_encode([]);
    exit;
}

[$year, $mon] = explode("-", $month);

$sql = "
    SELECT 
        i.item_name AS item_purchase,
        SUM(pi.quantity) AS total_sold,
        SUM(pi.quantity * pi.price) AS total_income
    FROM purchased p
    JOIN purchase_items pi ON pi.purchase_id = p.purchased_sid
    JOIN inventory i ON i.inventory_id = pi.inventory_id
    WHERE p.establishment = ?
      AND YEAR(p.date_purchase) = ?
      AND MONTH(p.date_purchase) = ?
    GROUP BY i.item_name
    ORDER BY total_sold DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $establishment, $year, $mon);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode($result->fetch_all(MYSQLI_ASSOC));
