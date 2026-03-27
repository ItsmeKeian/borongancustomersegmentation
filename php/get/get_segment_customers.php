<?php
session_start();
require_once("../dbconnect.php");
header('Content-Type: application/json');

$segment = strtoupper(trim($_GET['segment'] ?? ''));
$establishment = $_SESSION['business_name'] ?? null;

if (!$establishment) {
    echo json_encode(["status" => 0, "customers" => []]);
    exit;
}

$customers = [];

if ($segment === 'LOYAL CUSTOMERS') {

    $sql = "
        SELECT
            c.customer_sid,
            c.full_name,
            c.age,
            c.gender,
            c.occupation,
            c.education,
            c.location,
            c.email,
            MAX(p.date_purchase) AS last_purchase,
            SUM(p.total) AS total_spent
        FROM purchased p
        INNER JOIN customer c
            ON p.customer_sid = c.customer_sid
           AND p.establishment = c.establishment
        WHERE p.establishment = ?
          AND p.date_purchase >= DATE_SUB(NOW(), INTERVAL 2 MONTH)
          AND p.total >= 10
        GROUP BY c.customer_sid
        HAVING COUNT(*) >= 16
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $establishment);

} else {

    switch ($segment) {
        case 'PROFESSIONALS':
            $condition = "
                c.occupation IS NOT NULL
                AND c.occupation <> ''
                AND LOWER(c.occupation) NOT IN ('student','none','n/a')
            ";
            break;

        case 'STUDENTS':
            $condition = "
                c.education IN ('Elementary','High School','College','Vocational','Post Graduate')
            ";
            break;

        default:
            $condition = "UPPER(c.segment) = ?";
            break;
    }

    $sql = "
        SELECT
            c.customer_sid,
            c.full_name,
            c.age,
            c.gender,
            c.occupation,
            c.education,
            c.location,
            c.email,
            MAX(p.date_purchase) AS last_purchase,
            IFNULL(SUM(p.total),0) AS total_spent
        FROM customer c
        LEFT JOIN purchased p
            ON p.customer_sid = c.customer_sid
           AND p.establishment = c.establishment
        WHERE c.establishment = ?
          AND $condition
        GROUP BY c.customer_sid
    ";

    if ($condition === "UPPER(c.segment) = ?") {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $establishment, $segment);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $establishment);
    }
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}

echo json_encode(["status" => 1, "customers" => $customers]);
