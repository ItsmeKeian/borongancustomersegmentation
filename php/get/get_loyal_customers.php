<?php
require_once("../dbconnect.php");
session_start();

$establishment = $_SESSION['business_name'] ?? null;

if ($establishment === null) {
    echo json_encode(["status" => 0, "message" => "Unauthorized"]);
    exit();
}

$sql = "
    SELECT 
    c.full_name,
    c.age,
    c.gender,
    c.location,
    c.email,
    c.total_spent,
    c.occupation,
    c.education,

    (
        SELECT MAX(p.date_purchase)
        FROM purchased p
        WHERE p.customer_sid = c.customer_sid
          AND p.establishment = ?
    ) AS last_purchase

FROM customer c
WHERE c.establishment = ?
  AND c.is_loyal = 1

";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $establishment);
$stmt->execute();
$result = $stmt->get_result();

$customers = [];

while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}

echo json_encode([
    "status" => 1,
    "customers" => $customers
]);
