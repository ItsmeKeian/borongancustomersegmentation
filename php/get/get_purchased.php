<?php
session_start();
include('../dbconnect.php');
header('Content-Type: application/json');

if (!isset($_POST['id'])) {
    echo json_encode(["status" => 0, "message" => "Missing ID"]);
    exit;
}

$id = intval($_POST['id']);

try {
    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ GET RECEIPT
    $stmt = $dbh->prepare("
        SELECT full_name, date_purchase, total
        FROM purchased 
        WHERE purchased_sid = ?
    ");
    $stmt->execute([$id]);
    $receipt = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$receipt) {
        echo json_encode(["status" => 0, "message" => "Receipt not found"]);
        exit;
    }

    // ✅ GET ITEMS
    $itemsStmt = $dbh->prepare("
        SELECT item_name, price, quantity, subtotal
        FROM purchase_items
        WHERE purchase_id = ?
    ");
    $itemsStmt->execute([$id]);
    $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => 1,
        "receipt" => $receipt,
        "items" => $items
    ]);

} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => $e->getMessage()]);
}
