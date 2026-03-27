<?php
session_start();
include('../dbconnect.php');
require __DIR__ . '/../../vendor/autoload.php';
include_once '../fetch_logs.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
        echo json_encode(["status" => 0, "message" => "Unauthorized access"]);
        exit;
    }

    $userId = $_SESSION['user_id'] ?? null;
    $business_name = $_SESSION['business_name'] ?? null;

    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== 0) {
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
            INSERT INTO customer (
                full_name,
                age,
                gender,
                location,
                email,
                phone,
                segment,
                establishment,
                occupation,
                estimated_income,
                education,
                created_at
            ) VALUES (
                :full_name,
                :age,
                :gender,
                :location,
                :email,
                :phone,
                :segment,
                :establishment,
                :occupation,
                :estimated_income,
                :education,
                NOW()
            )
        ");

        $stmt->execute([
            ":full_name"        => $row[0]  ?? null,
            ":age"              => $row[1]  ?? null,
            ":gender"           => $row[2]  ?? null,
            ":location"         => $row[3]  ?? null,
            ":email"            => $row[4]  ?? null,
            ":phone"            => $row[5]  ?? null,
            ":segment"          => $row[6]  ?? null,
            ":establishment"    => $row[7]  ?? $business_name,
            ":occupation"       => $row[8]  ?? null,
            ":estimated_income" => $row[9]  ?? null,
            ":education"        => $row[10] ?? null
        ]);

        $rowCount++;
    }

    // Log activity
    if ($userId && $rowCount > 0) {
        $logDetails = "Imported {$rowCount} customer records from Excel";
        logAction($dbh, $userId, 'Import Customers', $logDetails);
    }

    echo json_encode(["status" => 1, "message" => "Imported $rowCount records successfully"]);

} catch (Throwable $e) {
    echo json_encode(["status" => 0, "message" => "Error: " . $e->getMessage()]);
}
