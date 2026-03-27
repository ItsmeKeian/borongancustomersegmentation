<?php
require_once('../dbconnect.php');
header('Content-Type: application/json');

$establishment = $_GET['est'] ?? '';

if (empty($establishment)) {
    echo json_encode(["status" => 0, "message" => "Missing establishment"]);
    exit();
}

try {
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $dbh->prepare("
        SELECT DISTINCT name 
        FROM segments 
        WHERE establishment = :establishment
        ORDER BY name ASC
    ");
    $stmt->bindParam(':establishment', $establishment);
    $stmt->execute();

    echo json_encode(["status" => 1, "segments" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => $e->getMessage()]);
}
