<?php
session_start();
include('../dbconnect.php');
header('Content-Type: application/json');

if (!isset($_SESSION['business_name'])) {
    echo json_encode(["status" => 0, "message" => "No establishment session"]);
    exit;
}

$establishment = $_SESSION['business_name'];
$item_name = $_POST['item_name'] ?? '';
$price = $_POST['price'] ?? '';
$quantity = $_POST['quantity'] ?? '';

if (!$item_name || !$price || !$quantity) {
    echo json_encode(["status" => 0, "message" => "All fields are required"]);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO inventory (establishment, item_name, price, quantity)
    VALUES (?, ?, ?, ?)
");

$stmt->bind_param("ssdi", $establishment, $item_name, $price, $quantity);

if ($stmt->execute()) {
    echo json_encode(["status" => 1]);
} else {
    echo json_encode(["status" => 0, "message" => $stmt->error]);
}
