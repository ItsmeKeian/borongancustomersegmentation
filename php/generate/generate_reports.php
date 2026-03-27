<?php
session_start();
include('../dbconnect.php');
include_once '../fetch_logs.php';

// Check user role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    die("Unauthorized access");
}

$business_name = $_SESSION['business_name'];

// Get filters
$reportType = isset($_GET['reportType']) ? $_GET['reportType'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

if (empty($reportType) || empty($start_date) || empty($end_date)) {
    die("Please select a report type and date range.");
}

try {
    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // Log report generation
      
    // Choose query based on report type
    switch ($reportType) {
        case 'campaigns':
            $query = "
                SELECT campaign_name, target_segment, message, sent_count, created_at
                FROM campaigns
                WHERE establishment = :bname
                AND DATE(created_at) BETWEEN :start_date AND :end_date
                ORDER BY created_at DESC
            ";
            $headers = ['Campaign Name', 'Target Segment', 'Message', 'Sent Count', 'Created At'];
            break;

        case 'customer':
            $query = "
                SELECT full_name, age, gender, phone, email, location, segment, purchase_count, total_spent, created_at
                FROM customer
                WHERE establishment = :bname
                AND DATE(created_at) BETWEEN :start_date AND :end_date
                ORDER BY created_at DESC
            ";
            $headers = ['Full Name', 'Age', 'Gender', 'Phone', 'Email', 'Location', 'Segment', 'Purchase Count', 'Total Spent', 'Created At'];
            break;

        case 'purchased':
    $query = "
        SELECT 
            c.full_name,
            pi.item_name,
            pi.price,
            pi.quantity,
            pi.subtotal,
            p.date_purchase
        FROM purchased p
        JOIN customer c ON p.customer_sid = c.customer_sid
        JOIN purchase_items pi ON p.purchased_sid = pi.purchase_id
        WHERE p.establishment = :bname
        AND DATE(p.date_purchase) BETWEEN :start_date AND :end_date
        ORDER BY p.date_purchase DESC
    ";

    $headers = ['Full Name','Item','Price','Qty','Total','Date'];
    break;


            $headers = ['Full Name','Item','Price','Qty','Total','Date'];
            break;


        case 'segments':
            $query = "
                SELECT name, description, criteria, age_min, age_max, created_at
                FROM segments
                WHERE establishment = :bname
                AND DATE(created_at) BETWEEN :start_date AND :end_date
                ORDER BY created_at DESC
            ";
            $headers = ['Segment Name', 'Age_min', 'Age_max', 'Description', 'Criteria', 'Created At'];
            break;

        default:
            die("Invalid report type.");
    }

    // Prepare statement
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':bname', $business_name, PDO::PARAM_STR);
    $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
    $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($data)) {
        die("No records found for the selected date range.");
    }

    // ========================================
    // Generate Excel
    // ========================================
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=report_" . $reportType . "_" . date("Y-m-d_H-i-s") . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Print column headers
    echo implode("\t", $headers) . "\n";

    // Print data rows
    foreach ($data as $row) {
        echo implode("\t", $row) . "\n";
    }

      logAction($dbh, $_SESSION['user_id'], 'Export Report', "Report Type: $reportType, Date Range: $start_date to $end_date");

    exit();

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>
