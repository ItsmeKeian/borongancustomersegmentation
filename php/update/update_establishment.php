<?php
include('../dbconnect.php');
header('Content-Type: application/json');

try {
    if (!isset($_POST['id'])) {
        echo json_encode(["status" => 0, "message" => "No ID provided"]);
        exit;
    }

    $id = intval($_POST['id']);
    $business_name   = $_POST['business_name'] ?? '';
    $business_type   = $_POST['business_type'] ?? '';
    $owners_name     = $_POST['owners_name'] ?? '';
    $email           = $_POST['email'] ?? '';
    $contact         = $_POST['contact'] ?? '';
    $address         = $_POST['address'] ?? '';
    $date_time         = $_POST['date_time'] ?? '';
    $password        = $_POST['password'] ?? '';
    $confirmpassword = $_POST['confirmpassword'] ?? '';

    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $dbh->prepare("
        UPDATE establishment 
        SET business_name   = :business_name,
            business_type   = :business_type,
            owners_name     = :owners_name,
            email           = :email,
            contact         = :contact,
            address         = :address,
            password        = :password,
            date_time        = :date_time,
            confirmpassword = :confirmpassword
        WHERE establishment_sid = :id
    "); 

    $stmt->bindParam(":business_name", $business_name);
    $stmt->bindParam(":business_type", $business_type);
    $stmt->bindParam(":owners_name", $owners_name);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":contact", $contact);
    $stmt->bindParam(":address", $address);
    $stmt->bindParam(":password", $password);
     $stmt->bindParam(":date_time", $date_time);
    $stmt->bindParam(":confirmpassword", $confirmpassword);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(["status" => 1, "message" => "Record updated successfully"]);
    } else {
        echo json_encode(["status" => 0, "message" => "Failed to update record"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => "Database error: " . $e->getMessage()]);
}
