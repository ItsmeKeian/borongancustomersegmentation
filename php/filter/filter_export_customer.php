<?php
include_once __DIR__ . "/../dbconnect.php";
include_once __DIR__ . "/../fetch_logs.php"; // ✅ Include logging file
session_start();

// ✅ Check session
if (!isset($_SESSION['business_name']) || !isset($_SESSION['user_id'])) {
    die("Session expired. Please log in again.");
}

$establishment = $conn->real_escape_string($_SESSION['business_name']);
$userId = $_SESSION['user_id']; // ✅ user ID for logs

// Get filters from GET
$segment  = $_GET['segment']  ?? null;
$name     = $_GET['name']     ?? null;
$location = $_GET['location'] ?? null;
$minAge   = $_GET['minAge']   ?? null;
$maxAge   = $_GET['maxAge']   ?? null;

// Base query
$sql = "SELECT full_name, age, gender, location, email, phone, segment 
        FROM customer 
        WHERE establishment = '$establishment'";

// Apply filters dynamically
if (!empty($segment)) {

    switch (strtoupper($segment)) {

        case 'STUDENTS':
            $sql .= " 
                AND education IS NOT NULL 
                AND education NOT IN ('None','High School Graduate','College Graduate')
            ";
            break;

        case 'PROFESSIONALS':
            $sql .= "
                AND occupation IS NOT NULL
                AND occupation NOT IN ('Students','None','N/A','')
            ";
            break;

        case 'KIDS / TEENS':
            $sql .= " AND age < 18";
            break;

        case 'YOUNG ADULTS':
            $sql .= " AND age BETWEEN 18 AND 30";
            break;

        case 'ADULTS':
            $sql .= " AND age BETWEEN 31 AND 59";
            break;

        case 'SENIORS':
            $sql .= " AND age >= 60";
            break;

       

        case 'LOYAL CUSTOMERS':
            $sql .= "
                AND customer_sid IN (
                    SELECT customer_sid
                    FROM purchased
                    WHERE establishment = '$establishment'
                    AND date_purchase >= DATE_SUB(NOW(), INTERVAL 2 MONTH)
                    GROUP BY customer_sid
                    HAVING COUNT(*) >= 16
                    AND MIN(total) >= 10
                )
            ";
            break;

        default:
            // MANUAL SEGMENTS ONLY
            $segment = $conn->real_escape_string($segment);
            $sql .= " AND segment = '$segment'";
    }
}


if (!empty($name)) {
    $name = $conn->real_escape_string($name);
    $sql .= " AND full_name LIKE '%$name%'";
}

if (!empty($location)) {
    $location = $conn->real_escape_string($location);
    $sql .= " AND location LIKE '%$location%'";
}

if (!empty($minAge) && !empty($maxAge)) {
    $sql .= " AND age BETWEEN " . intval($minAge) . " AND " . intval($maxAge);
} elseif (!empty($minAge)) {
    $sql .= " AND age >= " . intval($minAge);
} elseif (!empty($maxAge)) {
    $sql .= " AND age <= " . intval($maxAge);
}

// Run query
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    die("No data to export.");
}

// ✅ Prepare log details
$filterDetails = [];
if ($segment) $filterDetails[] = "Segment: $segment";
if ($name) $filterDetails[] = "Name: $name";
if ($location) $filterDetails[] = "Location: $location";
if ($minAge) $filterDetails[] = "Min Age: $minAge";
if ($maxAge) $filterDetails[] = "Max Age: $maxAge";

$logMessage = "Filter segment data";
if (!empty($filterDetails)) {
    $logMessage .= " with filters - " . implode(", ", $filterDetails);
}

if ($pdo) {
    logAction($pdo, $userId, 'Export filter segment', $logMessage);
}

// File name for download
$filename = "customers_export_" . date('Y-m-d') . ".xls";

// Set headers for Excel file
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");

// Start Excel table
echo "<table border='1'>";
echo "<tr>
        <th>Full Name</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Location</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Segment</th>
      </tr>";

// Output each row
while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['full_name']}</td>
            <td>{$row['age']}</td>
            <td>{$row['gender']}</td>
            <td>{$row['location']}</td>
            <td>{$row['email']}</td>
            <td>{$row['phone']}</td>
            <td>{$row['segment']}</td>
          </tr>";
}

echo "</table>";
exit();
?>
