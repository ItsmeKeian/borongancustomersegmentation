<?php
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
        c.customer_sid,
        c.full_name,
        c.age,
        c.gender,
        c.location,
        c.email,
        c.phone,
        c.segment,
        c.occupation,
        c.estimated_income,
        c.education,
        c.created_at,

        COUNT(p.total) AS purchase_count,
        COALESCE(SUM(p.total), 0) AS total_spent,
        MAX(p.date_purchase) AS last_purchase

    FROM customer c
    LEFT JOIN purchased p 
        ON c.full_name = p.full_name 
        AND c.establishment = p.establishment

    WHERE c.customer_sid = :id 
    GROUP BY c.customer_sid
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
