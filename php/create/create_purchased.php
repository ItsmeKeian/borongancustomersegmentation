<?php
session_start();
include('../dbconnect.php');  
include_once '../fetch_logs.php'; 

header('Content-Type: application/json');

// ✅ SECURITY CHECK
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    echo json_encode(["status" => 0, "message" => "Unauthorized"]);
    exit();
}

$establishment = $_SESSION['business_name'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

if (!$establishment) {
    echo json_encode(["status" => 0, "message" => "No establishment in session"]);
    exit();
}

try {
    // ✅ GET POST DATA
    $fullname       = $_POST['fname'] ?? '';
    $itempurchase   = $_POST['item_purchase'] ?? '';
    $itemprice      = $_POST['item_price'] ?? '';
    $datepurchase   = $_POST['date_purchase'] ?? '';
    $itemquantity   = $_POST['quantity'] ?? '';
    $itemtotal      = $_POST['total'] ?? '';

    // ✅ PDO CONNECTION (KEEP THIS — SAME AS YOUR CURRENT)
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ 1. FIND CUSTOMER ID
    $find = $dbh->prepare("
        SELECT customer_sid 
        FROM customer 
        WHERE full_name = :fname AND establishment = :establishment
        LIMIT 1
    ");
    $find->bindParam(":fname", $fullname);
    $find->bindParam(":establishment", $establishment);
    $find->execute();

    $customer_sid = $find->fetchColumn();

    if (!$customer_sid) {
        echo json_encode(["status" => 0, "message" => "Customer not found in your establishment records."]);
        exit();
    }

    // ✅ 2. CHECK INVENTORY STOCK FIRST
    $checkStock = $dbh->prepare("
        SELECT quantity 
        FROM inventory 
        WHERE item_name = :item AND establishment = :establishment
        LIMIT 1
    ");
    $checkStock->bindParam(":item", $itempurchase);
    $checkStock->bindParam(":establishment", $establishment);
    $checkStock->execute();

    $stock = $checkStock->fetchColumn();

    if (!$stock || $stock < $itemquantity) {
        echo json_encode(["status" => 0, "message" => "Not enough stock available"]);
        exit();
    }

    // ✅ 3. INSERT PURCHASE
    $qry = "
        INSERT INTO purchased (
            customer_sid, full_name, item_purchase, item_price, date_purchase, quantity, total, establishment
        ) VALUES (
            :customer_sid, :fname, :item_purchase, :item_price, :date_purchase, :quantity, :total, :establishment
        )
    ";
    $stmt = $dbh->prepare($qry);
    $stmt->bindParam(":customer_sid", $customer_sid);
    $stmt->bindParam(":fname", $fullname);
    $stmt->bindParam(":item_purchase", $itempurchase);
    $stmt->bindParam(":item_price", $itemprice);
    $stmt->bindParam(":date_purchase", $datepurchase);
    $stmt->bindParam(":quantity", $itemquantity);
    $stmt->bindParam(":total", $itemtotal);
    $stmt->bindParam(":establishment", $establishment);

    if ($stmt->execute()) {

        // ✅ 4. DEDUCT INVENTORY STOCK
        $deduct = $dbh->prepare("
            UPDATE inventory 
            SET quantity = quantity - :qty
            WHERE item_name = :item AND establishment = :establishment
        ");
        $deduct->bindParam(":qty", $itemquantity, PDO::PARAM_INT);
        $deduct->bindParam(":item", $itempurchase);
        $deduct->bindParam(":establishment", $establishment);
        $deduct->execute();

        // ✅ 5. UPDATE CUSTOMER TOTALS
        $update = $dbh->prepare("
            UPDATE customer c
            SET 
                c.purchase_count = (SELECT COUNT(*) FROM purchased p WHERE p.customer_sid = c.customer_sid),
                c.total_spent = (SELECT COALESCE(SUM(total), 0) FROM purchased WHERE customer_sid = c.customer_sid)
            WHERE c.customer_sid = :customer_sid
        ");
        $update->bindParam(":customer_sid", $customer_sid);
        $update->execute();

        // ✅ 6. LOG ACTION
        if ($userId) {
    $logDetails = [
        
        "full_name"   => $fullname,
        "item"        => $itempurchase,
        "price"       => $itemprice,
        "quantity"    => $itemquantity,
        "total"       => $itemtotal,
        "date"        => $datepurchase
    ];

    logAction(
        $dbh,
        $userId,
        "Add Purchase",
        $logDetails,
        $establishment
    );
}


        echo json_encode(["status" => 1, "message" => "Purchase saved & inventory updated successfully."]);

    } else {
        echo json_encode(["status" => 0, "message" => "Failed to save purchase."]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => "Error: " . $e->getMessage()]);
}
?>
