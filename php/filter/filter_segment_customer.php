<?php
header('Content-Type: application/json');
include_once __DIR__ . "/../dbconnect.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION['business_name'])) {
    echo json_encode(["status" => 0, "message" => "Session expired. Please log in."]);
    exit();
}

$establishment = $conn->real_escape_string($_SESSION['business_name']);

// Pagination
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$limit = isset($_POST['limit']) ? intval($_POST['limit']) : 10;
$offset = ($page - 1) * $limit;

// Filters
$segment  = $_POST['segment']  ?? null;
$name     = $_POST['name']     ?? null;
$location = $_POST['location'] ?? null;
$minAge   = $_POST['minAge']   ?? null;
$maxAge   = $_POST['maxAge']   ?? null;

// ✅ Check if NO filters were provided
if (
    empty($segment) &&
    empty($name) &&
    empty($location) &&
    empty($minAge) &&
    empty($maxAge)
) {
    echo json_encode(["status" => 0, "message" => "Please select a filter and enter a value."]);
    exit();
}

// Base SQL query
$sql = "SELECT * FROM customer WHERE establishment = '$establishment'";

// Apply filters dynamically
if (!empty($segment)) {

    switch (strtoupper($segment)) {

        case 'PROFESSIONALS':
            $sql .= "
                AND occupation IS NOT NULL
                AND occupation <> ''
                AND LOWER(occupation) NOT IN ('student', 'none', 'n/a')
            ";
            break;

        case 'STUDENTS':
            $sql .= "
                AND education IN (
                    'Elementary',
                    'High School',
                    'College',
                    'Vocational',
                    'Post Graduate'
                )
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
                    SELECT p.customer_sid
                    FROM purchased p
                    WHERE p.establishment = '$establishment'
                      AND p.date_purchase >= DATE_SUB(NOW(), INTERVAL 2 MONTH)
                      AND p.total >= 10
                    GROUP BY p.customer_sid
                    HAVING COUNT(*) >= 16
                )
            ";
            break;

        default:
            // MANUAL SEGMENTS
            $segment = $conn->real_escape_string($segment);
            $sql .= " AND UPPER(segment) = '$segment'";
            break;
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

// Total count for pagination
$countQuery = str_replace("SELECT *", "SELECT COUNT(*) as total", $sql);
$countResult = $conn->query($countQuery);
$total = $countResult ? $countResult->fetch_assoc()['total'] : 0;

// Sort alphabetically
$sql .= " ORDER BY full_name ASC";

// Add pagination
$sql .= " LIMIT $offset, $limit";

$result = $conn->query($sql);
$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode(["status" => 1, "data" => $data, "total" => $total]);
} else {
    echo json_encode(["status" => 0, "message" => "No records found"]);
}
?>
