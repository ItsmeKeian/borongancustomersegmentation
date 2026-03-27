<?php
session_start();
include('../dbconnect.php');
include_once '../fetch_logs.php';

header('Content-Type: application/json');

// SECURITY CHECK
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    echo json_encode(["status" => 0, "message" => "Unauthorized"]);
    exit();
}

$establishment = $_SESSION['business_name'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

$fname = $_POST['fname'] ?? '';
$date_purchase = $_POST['date_purchase'] ?? '';
$cartJson = $_POST['cart'] ?? '';

if (!$establishment || !$fname || !$date_purchase || !$cartJson) {
    echo json_encode(["status" => 0, "message" => "Missing required data"]);
    exit();
}

$cart = json_decode($cartJson, true);

if (!is_array($cart) || count($cart) === 0) {
    echo json_encode(["status" => 0, "message" => "Cart is empty"]);
    exit();
}

try {
    $pdo->beginTransaction();

    // 1. FIND CUSTOMER
    $find = $pdo->prepare("
        SELECT customer_sid 
        FROM customer 
        WHERE full_name = :fname AND establishment = :establishment
        LIMIT 1
    ");
    $find->execute([
        ':fname' => $fname,
        ':establishment' => $establishment
    ]);

    $customer_sid = $find->fetchColumn();

    if (!$customer_sid) {
        throw new Exception("Customer not found.");
    }

    // 2. COMPUTE GRAND TOTAL
    $grandTotal = 0;
    foreach ($cart as $i) {
        $grandTotal += $i['subtotal'];
    }

    // 3. INSERT RECEIPT (purchased)
    $insertReceipt = $pdo->prepare("
        INSERT INTO purchased (customer_sid, full_name, date_purchase, total, establishment)
        VALUES (?, ?, ?, ?, ?)
    ");
    $insertReceipt->execute([
        $customer_sid,
        $fname,
        $date_purchase,
        $grandTotal,
        $establishment
    ]);

    $purchase_id = $pdo->lastInsertId();

    // STORE ALL ITEMS FOR LOGGING LATER
    $itemsLog = [];

    // 4. INSERT ITEMS + DEDUCT INVENTORY
    foreach ($cart as $item) {

        $invId  = (int)$item['inventory_id'];
        $name   = $item['item_name'];
        $price  = (float)$item['price'];
        $qty    = (int)$item['quantity'];
        $subtotal = (float)$item['subtotal'];

        // lock stock
        $check = $pdo->prepare("
            SELECT quantity 
            FROM inventory 
            WHERE inventory_id = ? AND establishment = ?
            FOR UPDATE
        ");
        $check->execute([$invId, $establishment]);
        $stock = $check->fetchColumn();

        if ($stock === false || $stock < $qty) {
            throw new Exception("Not enough stock for $name");
        }

        // insert item
        $insertItem = $pdo->prepare("
            INSERT INTO purchase_items 
            (purchase_id, inventory_id, item_name, price, quantity, subtotal)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $insertItem->execute([
            $purchase_id,
            $invId,
            $name,
            $price,
            $qty,
            $subtotal
        ]);

        // deduct stock
        $updateInv = $pdo->prepare("
            UPDATE inventory 
            SET quantity = quantity - ?
            WHERE inventory_id = ? AND establishment = ?
        ");
        $updateInv->execute([$qty, $invId, $establishment]);

        // add to log record
        $itemsLog[] = [
            "inventory_id" => $invId,
            "item_name"    => $name,
            "price"        => $price,
            "quantity"     => $qty,
            "subtotal"     => $subtotal
        ];
    }

    // 5. UPDATE CUSTOMER TOTALS
    $updateCust = $pdo->prepare("
        UPDATE customer c
        SET 
            c.purchase_count = (
                SELECT COUNT(*) FROM purchased p WHERE p.customer_sid = c.customer_sid
            ),
            c.total_spent = (
                SELECT COALESCE(SUM(total), 0) FROM purchased WHERE customer_sid = c.customer_sid
            )
        WHERE c.customer_sid = ?
    ");
    $updateCust->execute([$customer_sid]);



    // 5.1 CHECK LOYALTY
    $loyalCheckSQL = "
        SELECT COUNT(*) 
        FROM purchased 
        WHERE customer_sid = ? 
          AND establishment = ?
          AND date_purchase >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
    ";

    $checkStmt = $pdo->prepare($loyalCheckSQL);
    $checkStmt->execute([$customer_sid, $establishment]);
    $purchaseCount = $checkStmt->fetchColumn();

    if ($purchaseCount >= 4) {
        $updateLoyalSQL = "
            UPDATE customer 
            SET is_loyal = 1 
            WHERE customer_sid = ? 
              AND establishment = ?
        ";
        $updateStmt = $pdo->prepare($updateLoyalSQL);
        $updateStmt->execute([$customer_sid, $establishment]);
    }



    // 6. CREATE FULL JSON LOG
    if ($userId) {

        $logDetails = [
            
            "full_name"    => $fname,
            "date_purchase"=> $date_purchase,
            "grand_total"  => $grandTotal,
            "items"        => $itemsLog
        ];

        logAction($pdo, $userId, "Checkout", $logDetails, $establishment);
    }


    $pdo->commit();
    echo json_encode(["status" => 1]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(["status" => 0, "message" => $e->getMessage()]);
}
