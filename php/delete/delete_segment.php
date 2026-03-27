<?php
session_start();
include("../dbconnect.php");
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


    // ✅ 1. Kunin muna yung campaign name bago i-delete
    $getSegmentName = $dbh->prepare("SELECT name FROM segments WHERE id = :id");
    $getSegmentName->bindParam(":id", $id, PDO::PARAM_INT);
    $getSegmentName->execute();
    $SegmentName = $getSegmentName->fetch(PDO::FETCH_ASSOC);
    $name = $SegmentName['name'] ?? 'Unknown Segment ';


    // Delete segment by ID
    $stmt = $dbh->prepare("DELETE FROM segments WHERE id = :id LIMIT 1");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    if ($stmt->execute()) {

         if (isset($_SESSION['user_id'])) {
            logAction($dbh, $_SESSION['user_id'], 'Delete segment', "Deleted segment record: ({$name})");
        }

        echo json_encode(["status" => 1, "message" => "Segment deleted successfully"]);
    } else {
        echo json_encode(["status" => 0, "message" => "Failed to delete segment"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => $e->getMessage()]);
}
