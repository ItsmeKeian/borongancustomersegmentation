<?php
session_start();
require_once '../dbconnect.php';

if (!isset($_SESSION['business_name'])) {
    echo json_encode([]);
    exit;
}

$establishment = $_SESSION['business_name'];

$query = "
    SELECT customer_sid, full_name 
    FROM customer 
    WHERE establishment = ?
    ORDER BY full_name ASC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $establishment);
$stmt->execute();
$result = $stmt->get_result();

$customers = [];
while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}

echo json_encode($customers);
