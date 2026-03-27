<?php
session_start();
include('../dbconnect.php');

try {
    $username = isset($_POST['username']) ? $_POST['username'] : "";

    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Replace 'email' with the column you store user login info
    $qry = "SELECT business_name FROM establishment WHERE business_name = :value LIMIT 1";

    $stmt = $dbh->prepare($qry);
    $stmt->bindParam(":username", $username);
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        $response = array(
            "status" => 1,
            "business_name" => $data['business_name']
        );
    } else {
        $response = array("status" => 0);
    }

    echo json_encode($response);

} catch (PDOException $e) {
    echo json_encode(array("status" => 0, "error" => $e->getMessage()));
}

$dbh = null;
?>
