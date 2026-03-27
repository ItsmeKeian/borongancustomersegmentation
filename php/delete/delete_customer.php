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

    // Database connection
    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ 1. Fetch FULL customer details BEFORE deleting
    $getCustomer = $dbh->prepare("SELECT * FROM customer WHERE customer_sid = :id");
    $getCustomer->bindParam(":id", $id, PDO::PARAM_INT);
    $getCustomer->execute();
    $customer = $getCustomer->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        echo json_encode(["status" => 0, "message" => "Customer not found"]);
        exit;
    }

    // 🔹 Format full details into JSON for logs
    $detailsJson = json_encode([
       
        "full_name"         => $customer['full_name'],
        "age"               => $customer['age'],
        "gender"            => $customer['gender'],
        "location"          => $customer['location'],
        "email"             => $customer['email'],
        "phone"             => $customer['phone'],
        "segment"           => $customer['segment'],
        "occupation"        => $customer['occupation'],
        "estimated_income"  => $customer['estimated_income'],
        "education"         => $customer['education'],
        "deleted_at"        => date("Y-m-d H:i:s")
    ], JSON_UNESCAPED_UNICODE);

    // 2️⃣ Delete query
    $stmt = $dbh->prepare("DELETE FROM customer WHERE customer_sid = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {

        // 3️⃣ Log JSON details (NOT plain text)
        if (isset($_SESSION['user_id'])) {
            logAction(
                $dbh,
                $_SESSION['user_id'],
                'Delete Customer',
                $detailsJson
            );
        }

        echo json_encode(["status" => 1, "message" => "Record deleted"]);
    } else {
        echo json_encode(["status" => 0, "message" => "No record found with that ID"]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => $e->getMessage()]);
}
