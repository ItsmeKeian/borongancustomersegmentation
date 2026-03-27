<?php
session_start();
include('../dbconnect.php');
include_once '../fetch_logs.php';
header('Content-Type: application/json');

// SECURITY CHECK
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    die(json_encode(["status" => 0, "message" => "Unauthorized access"]));
}

$userId = $_SESSION['user_id'] ?? null;
$establishment = $_SESSION['business_name'] ?? null;

try { 
    // GET POST DATA
    $id = intval($_POST['id']);

    $newData = [
        "full_name"      => $_POST['full_name'] ?? '',
        "item_purchase"  => $_POST['item_purchase'] ?? '',
        "item_price"     => $_POST['item_price'] ?? '',
        "date_purchase"  => $_POST['date_purchase'] ?? '',
        "quantity"       => $_POST['quantity'] ?? '',
        "total"          => $_POST['total'] ?? '',
        "establishment"  => $establishment
    ];

    // DB CONNECTION
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // 1. GET FULL OLD PURCHASE DATA
    $getOld = $dbh->prepare("SELECT * FROM purchased WHERE purchased_sid = :id");
    $getOld->bindParam(":id", $id, PDO::PARAM_INT);
    $getOld->execute();
    $oldData = $getOld->fetch(PDO::FETCH_ASSOC);

    if (!$oldData) {
        echo json_encode(["status" => 0, "message" => "Record not found"]);
        exit();
    }


    // 2. DETECT CHANGES
    $changes = [];
    foreach ($newData as $key => $newValue) {
        $oldValue = $oldData[$key] ?? null;

        if ($newValue != $oldValue) {
            $changes[$key] = [
                "old" => $oldValue,
                "new" => $newValue
            ];
        }
    }

    // If no changes → do nothing
    if (empty($changes)) {
        echo json_encode(["status" => 1, "message" => "No changes detected"]);
        exit();
    }


    // 3. UPDATE QUERY
    $stmt = $dbh->prepare("
        UPDATE purchased 
        SET 
            full_name      = :full_name,
            item_purchase  = :item_purchase,
            item_price     = :item_price,
            date_purchase  = :date_purchase,
            quantity       = :quantity,
            total          = :total,
            establishment  = :establishment
        WHERE purchased_sid = :id
    ");

    $stmt->execute([
        ":full_name"     => $newData["full_name"],
        ":item_purchase" => $newData["item_purchase"],
        ":item_price"    => $newData["item_price"],
        ":date_purchase" => $newData["date_purchase"],
        ":quantity"      => $newData["quantity"],
        ":total"         => $newData["total"],
        ":establishment" => $establishment,
        ":id"            => $id
    ]);


    // 4. LOG THE UPDATE WITH JSON
    $logDetails = [
        "purchased_id" => $id,
        "before"       => $oldData,
        "after"        => $newData,
        "changes"      => $changes
    ];

    logAction($dbh, $userId, "Update Purchase", $logDetails, $establishment);


    echo json_encode(["status" => 1, "message" => "Record updated successfully"]);

} catch (PDOException $e) {

    echo json_encode([
        "status" => 0,
        "message" => "Database error: " . $e->getMessage()
    ]);

}
?>
