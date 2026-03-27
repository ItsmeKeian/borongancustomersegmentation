<?php
session_start();
include('../dbconnect.php');

$type = $_POST['type'] ?? "";

try {
    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $count = 0;

    if ($type === "Total2") {
        // Count unique customers (distinct full_name)
        $qry = "SELECT COUNT(DISTINCT business_type) AS cnt FROM establishment";
        $stmt = $dbh->query($qry);
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];

    } elseif ($type === "Total3") {
        // Count all establishments
        $qry = "SELECT COUNT(*) AS cnt FROM establishment";
        $stmt = $dbh->query($qry);
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
        
    }elseif ($type === "Total4") {
        // Count all system logs
        $qry = "SELECT COUNT(*) AS cnt FROM system_logs";
        $stmt = $dbh->query($qry);
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];

    }elseif ($type === "Total5") {
        // Count all failed logins
        $qry = "SELECT COUNT(*) AS cnt FROM failed_logins";
        $stmt = $dbh->query($qry);
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
    }
 

    echo json_encode(["status" => 1, "unid" => $count]);

} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => "Error: " . $e->getMessage()]);
}
?>
