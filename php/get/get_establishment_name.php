<?php
include '../dbconnect.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

if (isset($_SESSION['business_name'])) {
    echo json_encode([
        "success" => true,
        "business_name" => $_SESSION['business_name']
    ]);
} else {
    echo json_encode(["success" => false, "message" => "No establishment found"]);
}
