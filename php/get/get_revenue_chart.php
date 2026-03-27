<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . "/../dbconnect.php";

if (!isset($_SESSION['business_name'])) {
    echo json_encode(["status" => 0, "data" => []]);
    exit;
}

$establishment = $_SESSION['business_name'];

$dbh = new PDO(
    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
    DB_USER,
    DB_PASS,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

/*
|--------------------------------------------------------------------------
| Says: ALL SEGMENTS MUST BE COMPUTED
|--------------------------------------------------------------------------
*/

$sql = "

/* ================= MANUAL SEGMENTS ================= */
SELECT
    c.segment AS segment,
    DATE_FORMAT(p.date_purchase, '%b %Y') AS month,
    SUM(p.total) AS total_revenue
FROM purchased p
JOIN customer c 
  ON p.customer_sid = c.customer_sid
 AND p.establishment = c.establishment
WHERE p.establishment = :est
  AND c.segment IS NOT NULL
GROUP BY c.segment, DATE_FORMAT(p.date_purchase, '%Y-%m')

UNION ALL

/* ================= LOYAL CUSTOMERS ================= */
SELECT
    'LOYAL CUSTOMERS' AS segment,
    DATE_FORMAT(p.date_purchase, '%b %Y') AS month,
    SUM(p.total) AS total_revenue
FROM purchased p
WHERE p.establishment = :est
  AND p.date_purchase >= DATE_SUB(NOW(), INTERVAL 2 MONTH)
GROUP BY DATE_FORMAT(p.date_purchase, '%Y-%m')
HAVING COUNT(p.customer_sid) >= 16

UNION ALL

/* ================= STUDENTS ================= */
SELECT
    'STUDENTS' AS segment,
    DATE_FORMAT(p.date_purchase, '%b %Y') AS month,
    SUM(p.total) AS total_revenue
FROM purchased p
JOIN customer c ON p.customer_sid = c.customer_sid
WHERE p.establishment = :est
  AND c.education IN ('Elementary','High School','College','Vocational','Post Graduate')
GROUP BY DATE_FORMAT(p.date_purchase, '%Y-%m')

ORDER BY month
";

$stmt = $dbh->prepare($sql);
$stmt->execute(['est' => $establishment]);

echo json_encode([
    "status" => 1,
    "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)
]);
