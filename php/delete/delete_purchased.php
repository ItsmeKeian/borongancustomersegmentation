<?php 
session_start();
include('../dbconnect.php');
include_once '../fetch_logs.php';
header('Content-Type: application/json');

try {

    if (!isset($_POST['id'])) {
        echo json_encode(["status" => 0, "message" => "No ID provided"]);
        exit;
    }

    $id = intval($_POST['id']);

    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // 1️⃣ FETCH FULL PURCHASE DETAILS BEFORE DELETE
    $getPurchase = $dbh->prepare("
        SELECT * FROM purchased 
        WHERE purchased_sid = :id
    ");
    $getPurchase->bindParam(":id", $id, PDO::PARAM_INT);
    $getPurchase->execute();
    $purchase = $getPurchase->fetch(PDO::FETCH_ASSOC);

    if (!$purchase) {
        echo json_encode(["status" => 0, "message" => "Purchase not found"]);
        exit();
    }

    // 2️⃣ FETCH PURCHASE ITEMS ALSO (if POS checkout)
    $getItems = $dbh->prepare("
        SELECT item_name, price, quantity, subtotal 
        FROM purchase_items 
        WHERE purchase_id = :id
    ");
    $getItems->bindParam(":id", $id, PDO::PARAM_INT);
    $getItems->execute();
    $items = $getItems->fetchAll(PDO::FETCH_ASSOC);


    // 3️⃣ COMBINE DETAILS FOR LOG
    $logDetails = [
       
        
        "full_name"     => $purchase["full_name"],
        "date_purchase" => $purchase["date_purchase"],
        "total"         => $purchase["total"],
        "items"         => $items, // array of all items
        "deleted_at"    => date("Y-m-d H:i:s")
    ];


    // 4️⃣ DELETE PURCHASE
    $stmt = $dbh->prepare("DELETE FROM purchased WHERE purchased_sid = :id");
    $stmt->bindParam(":id", $id);

    if ($stmt->execute()) {

        // 5️⃣ INSERT LOG
        if (isset($_SESSION['user_id'])) {
            logAction(
                $dbh,
                $_SESSION['user_id'],
                'Delete Purchase',
                json_encode($logDetails, JSON_UNESCAPED_UNICODE),
                $_SESSION['business_name'] ?? null
            );
        }

        echo json_encode(["status" => 1, "message" => "Record deleted"]);
    } 
    else {
        echo json_encode(["status" => 0, "message" => "Delete failed"]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => $e->getMessage()]);
}
?>
