<?php
require_once __DIR__ . '/require_login.php';
require_role('Establishment');
require_once __DIR__ . '/dbconnect.php';

header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('Asia/Manila');

function runSelect(mysqli $conn, string $sql, string $types, ...$params): array
{
    $stmt = $conn->prepare($sql);
    if (!$stmt) return ['rows' => [], 'error' => $conn->error];

    if ($types !== '') $stmt->bind_param($types, ...$params);

    if (!$stmt->execute()) {
        $err = $stmt->error;
        $stmt->close();
        return ['rows' => [], 'error' => $err];
    }

    $res = $stmt->get_result();
    $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();

    return ['rows' => $rows, 'error' => null];
}

$establishment = $_SESSION['business_name'] ?? '';
if ($establishment === '') {
    echo json_encode(['success' => false]);
    exit;
}

/* ---------------- CURRENT MONTH TOTAL ---------------- */

$sqlCurrent = "
    SELECT SUM(pi.quantity) AS total_units
    FROM purchased p
    JOIN purchase_items pi ON pi.purchase_id = p.purchased_sid
    WHERE p.establishment = ?
      AND YEAR(p.date_purchase) = YEAR(CURDATE())
      AND MONTH(p.date_purchase) = MONTH(CURDATE())
";

$currentMonthTotal = runSelect($conn, $sqlCurrent, 's', $establishment)['rows'][0]['total_units'] ?? 0;

/* ---------------- TOP SELLER THIS WEEK ---------------- */

$sqlBestWeek = "
    SELECT i.item_name AS item_purchase,
           SUM(pi.quantity) AS total_sold,
           COUNT(DISTINCT p.customer_sid) AS unique_customers
    FROM purchased p
    JOIN purchase_items pi ON pi.purchase_id = p.purchased_sid
    JOIN inventory i ON i.inventory_id = pi.inventory_id
    WHERE p.establishment = ?
      AND YEARWEEK(p.date_purchase, 1) = YEARWEEK(CURDATE(), 1)
    GROUP BY i.item_name
    ORDER BY total_sold DESC
    LIMIT 1
";

$bestWeek = runSelect($conn, $sqlBestWeek, 's', $establishment)['rows'][0] ?? null;

/* ---------------- TOP SELLER LAST MONTH ---------------- */

$sqlBestMonth = "
    SELECT i.item_name AS item_purchase,
           SUM(pi.quantity) AS total_sold,
           COUNT(DISTINCT p.customer_sid) AS unique_customers
    FROM purchased p
    JOIN purchase_items pi ON pi.purchase_id = p.purchased_sid
    JOIN inventory i ON i.inventory_id = pi.inventory_id
    WHERE p.establishment = ?
      AND YEAR(p.date_purchase) = YEAR(CURDATE() - INTERVAL 1 MONTH)
      AND MONTH(p.date_purchase) = MONTH(CURDATE() - INTERVAL 1 MONTH)
    GROUP BY i.item_name
    ORDER BY total_sold DESC
    LIMIT 1
";

$bestMonth = runSelect($conn, $sqlBestMonth, 's', $establishment)['rows'][0] ?? null;

/* ---------------- WEEKLY ---------------- */

$sqlWeekly = "
    SELECT i.item_name AS item_purchase,
           SUM(pi.quantity) AS total_sold,
           COUNT(DISTINCT p.customer_sid) AS unique_customers
    FROM purchased p
    JOIN purchase_items pi ON pi.purchase_id = p.purchased_sid
    JOIN inventory i ON i.inventory_id = pi.inventory_id
    WHERE p.establishment = ?
      AND YEARWEEK(p.date_purchase, 1) = YEARWEEK(CURDATE(), 1)
    GROUP BY i.item_name
    ORDER BY total_sold DESC
";


$weekly = runSelect($conn, $sqlWeekly, 's', $establishment)['rows'];

/* ---------------- MONTHLY ---------------- */

$sqlMonthly = "
    SELECT i.item_name AS item_purchase,
           SUM(pi.quantity) AS total_sold,
           COUNT(DISTINCT p.customer_sid) AS unique_customers
    FROM purchased p
    JOIN purchase_items pi ON pi.purchase_id = p.purchased_sid
    JOIN inventory i ON i.inventory_id = pi.inventory_id
    WHERE p.establishment = ?
      AND YEAR(p.date_purchase) = YEAR(CURDATE())
      AND MONTH(p.date_purchase) = MONTH(CURDATE())
    GROUP BY i.item_name
    ORDER BY total_sold DESC
";


$monthly = runSelect($conn, $sqlMonthly, 's', $establishment)['rows'];

/* ---------------- FAST vs SLOW ---------------- */

$sqlTrend = "
    SELECT i.item_name AS item_purchase,
        SUM(p.date_purchase >= CURDATE() - INTERVAL 7 DAY) AS week1,
        SUM(p.date_purchase BETWEEN CURDATE() - INTERVAL 14 DAY AND CURDATE() - INTERVAL 8 DAY) AS week2
    FROM purchased p
    JOIN purchase_items pi ON pi.purchase_id = p.purchased_sid
    JOIN inventory i ON i.inventory_id = pi.inventory_id
    WHERE p.establishment = ?
    GROUP BY i.item_name
";

$trend = runSelect($conn, $sqlTrend, 's', $establishment)['rows'];

$fast = [];
$slow = [];

foreach ($trend as $row) {
    if ($row['week1'] > $row['week2']) $fast[] = $row;
    elseif ($row['week1'] < $row['week2']) $slow[] = $row;
}

/* ---------------- UNIQUE BUYERS ---------------- */

$sqlUnique = "
    SELECT 
        c.customer_sid,
        c.full_name,
        c.gender,
        c.location,
        c.segment,
        c.age,
        SUM(pi.quantity) AS total_items,
        SUM(pi.price * pi.quantity) AS total_spent,
        GROUP_CONCAT(DISTINCT i.item_name SEPARATOR ', ') AS items_bought
    FROM purchased p
    JOIN purchase_items pi ON pi.purchase_id = p.purchased_sid
    JOIN inventory i ON i.inventory_id = pi.inventory_id
    JOIN customer c ON c.customer_sid = p.customer_sid
    WHERE p.establishment = ?
      AND YEARWEEK(p.date_purchase, 1) = YEARWEEK(CURDATE(), 1)
    GROUP BY c.customer_sid
";

$uniqueBuyers = runSelect($conn, $sqlUnique, 's', $establishment)['rows'];

echo json_encode([
    'success' => true,
    'bestWeek' => $bestWeek,
    'bestMonth' => $bestMonth,
    'weekly' => $weekly,
    'monthly' => $monthly,
    'fast' => $fast,
    'slow' => $slow,
    'uniqueBuyers' => $uniqueBuyers,
    'uniqueBuyersCount' => count($uniqueBuyers),
    'currentMonthTotal' => $currentMonthTotal
]);
