<?php
session_start();
include('../dbconnect.php');
require __DIR__ . '/../../vendor/autoload.php';
include_once '../fetch_logs.php'; // ✅ Include log function

use PhpOffice\PhpSpreadsheet\IOFactory;

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
        echo json_encode(["status" => 0, "message" => "Unauthorized access"]);
        exit;
    }

    // ✅ Get user info from session
    $userId = $_SESSION['user_id'] ?? null;
    $business_name = $_SESSION['business_name'] ?? null;

    if (!isset($_FILES['file']) || $_FILES['file']['error'] != 0) {
        echo json_encode(["status" => 0, "message" => "No file uploaded"]);
        exit;
    }

    $fileName = $_FILES['file']['tmp_name'];

    $spreadsheet = IOFactory::load($fileName);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $rowCount = 0;
    $skipHeader = true;

    foreach ($rows as $row) {
        if ($skipHeader) { 
            $skipHeader = false;    
            continue; 
        }

        $stmt = $dbh->prepare("
            INSERT INTO purchased (full_name, item_purchase, item_price, date_purchase, quantity, total, establishment) 
            VALUES (:full_name, :item_purchase, :item_price, :date_purchase, :quantity, :total, :establishment)
        ");

        $stmt->execute([
            ":full_name"     => $row[0] ?? null,
            ":item_purchase" => $row[1] ?? null,
            ":item_price"    => $row[2] ?? 0,
            ":date_purchase" => $row[3] ?? null,
            ":quantity"      => $row[4] ?? 0,
            ":total"         => $row[5] ?? 0,
            ":establishment" => $row[6] ?? $business_name // fallback to logged in establishment
        ]);

        $rowCount++;
    }

    // ========================================
    // ✅ Log the import action
    // ========================================
    if ($userId && $rowCount > 0) {
        $logDetails = "Imported {$rowCount} purchased records from Excel";
        logAction($dbh, $userId, 'Import Purchased', $logDetails);
    }

    echo json_encode(["status" => 1, "message" => "Imported $rowCount purchased records successfully"]);
} catch (Throwable $e) {
    echo json_encode(["status" => 0, "message" => "Error: " . $e->getMessage()]);
}
