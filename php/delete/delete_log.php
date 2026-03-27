<?php
require_once "../require_login.php";
require_role("Establishment");
require_once "../dbconnect.php";

header("Content-Type: application/json");

$id = $_GET['id'] ?? 0;

if (!$id) {
    echo json_encode(["status" => 0, "message" => "Invalid ID"]);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM system_logs WHERE systemlog_sid = :id");
$stmt->execute([':id' => $id]);

echo json_encode(["status" => 1]);
