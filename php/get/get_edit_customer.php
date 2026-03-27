<?php
session_start();
include('../dbconnect.php');
header('Content-Type: application/json');

try {
    if (!isset($_POST['id'])) {
        echo json_encode(["status" => 0, "message" => "No ID provided"]);
        exit;
    }

    

    $id = intval($_POST['id']);

    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $dbh->prepare("
            SELECT 
                customer_sid,
                full_name,
                age,
                gender,
                location,
                email,
                phone,
                segment,
                occupation,
                estimated_income,
                education,
                establishment,
                created_at
            FROM customer 
            WHERE customer_sid = :id 
            LIMIT 1
        ");


    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

   if ($data) {
    if (!empty($data['created_at'])) {
        // For datetime-local input
        $data['created_at_iso'] = date("Y-m-d\TH:i", strtotime($data['created_at']));
        // For viewing
        $data['created_at_formatted'] = date("F j, Y - g:i A", strtotime($data['created_at']));
    }

    echo json_encode(["status" => 1, "data" => $data]);
} else {
        echo json_encode(["status" => 0, "message" => "Record not found"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => "Database error: " . $e->getMessage()]);
}
