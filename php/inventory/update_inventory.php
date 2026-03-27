<?php
session_start();
include('../dbconnect.php');
header('Content-Type: application/json');

$id = $_POST['id'] ?? 0;
$item_name = $_POST['item_name'] ?? null;
$price = $_POST['price'] ?? null;
$quantity = $_POST['quantity'] ?? null;
$restock = $_POST['restock'] ?? 0;

if ($id == 0) {
    echo json_encode(["status" => 0, "message" => "Missing ID"]);
    exit;
}

/* ✅ ===== RESTOCK MODE ===== */
if ($restock > 0) {

    // Get current stock
    $check = $conn->prepare("SELECT quantity FROM inventory WHERE inventory_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $result = $check->get_result()->fetch_assoc();

    $newQty = $result['quantity'] + $restock;
    $status = $newQty > 0 ? "Available" : "Out of Stock";

    $stmt = $conn->prepare("
        UPDATE inventory 
        SET quantity = ?, status = ?
        WHERE inventory_id = ?
    ");
    $stmt->bind_param("isi", $newQty, $status, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => 1]);
    } else {
        echo json_encode(["status" => 0, "message" => $stmt->error]);
    }
    exit;
}

/* ✅ ===== FULL EDIT MODE ===== */
$status = $quantity > 0 ? "Available" : "Out of Stock";

$stmt = $conn->prepare("
    UPDATE inventory 
    SET item_name = ?, price = ?, quantity = ?, status = ?
    WHERE inventory_id = ?
");

$stmt->bind_param("sdisi", $item_name, $price, $quantity, $status, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => 1]);
} else {
    echo json_encode(["status" => 0, "message" => $stmt->error]);
}
