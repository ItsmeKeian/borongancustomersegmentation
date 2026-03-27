<?php
include('../dbconnect.php');
header('Content-Type: application/json');

$id = $_POST['id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM inventory WHERE inventory_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["status" => 1]);
} else {
    echo json_encode(["status" => 0, "message" => $stmt->error]);
}
