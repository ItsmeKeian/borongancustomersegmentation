<?php
session_start();
require_once('../dbconnect.php');

$est = $_SESSION['business_name'];

$dbh = new PDO(
    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
    DB_USER,
    DB_PASS,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$data = [];

/* ================= MANUAL SEGMENTS ================= */
$stmt = $dbh->prepare("
    SELECT segment, COUNT(*) as count
    FROM customer
    WHERE establishment = ?
      AND segment IS NOT NULL
    GROUP BY segment
");
$stmt->execute([$est]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ================= AUTO SEGMENTS ================= */
$auto = [
    "LOYAL CUSTOMERS" => "
        SELECT COUNT(DISTINCT customer_sid)
        FROM purchased
        WHERE establishment = ?
        AND date_purchase >= DATE_SUB(NOW(), INTERVAL 2 MONTH)
        GROUP BY customer_sid
        HAVING COUNT(*) >= 16
    ",
    "STUDENTS" => "
        SELECT COUNT(*)
        FROM customer
        WHERE establishment = ?
        AND education IN ('Elementary','High School','College','Vocational','Post Graduate')
    ",
    "PROFESSIONALS" => "
        SELECT COUNT(*)
        FROM customer
        WHERE establishment = ?
        AND occupation IS NOT NULL
        AND occupation <> ''
    "
];

foreach ($auto as $name => $sql) {
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$est]);
    $count = $stmt->rowCount();

    $data[] = [
        "segment" => $name,
        "count" => $count
    ];
}

echo json_encode($data);
