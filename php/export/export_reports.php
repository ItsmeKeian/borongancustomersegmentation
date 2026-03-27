<?php
session_start();
include('../dbconnect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    die("Unauthorized access");
}

$business_name = $_SESSION['business_name'];
$search = $_GET['search'] ?? "";

$dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$where = "WHERE p.establishment = :bname";
if ($search !== "") {
    $where .= " AND (c.full_name LIKE :search OR pi.item_name LIKE :search)";
}

$query = "
    SELECT 
        c.full_name,
        c.age,
        c.gender,
        c.phone,
        c.email,
        c.location,
        pi.item_name,
        pi.price,
        pi.quantity,
        pi.subtotal,
        p.date_purchase
    FROM purchased p
    JOIN customer c ON p.customer_sid = c.customer_sid
    JOIN purchase_items pi ON p.purchased_sid = pi.purchase_id
    $where
    ORDER BY p.date_purchase DESC
";

$stmt = $dbh->prepare($query);
$stmt->bindParam(':bname', $business_name);

if ($search !== "") {
    $like = "%$search%";
    $stmt->bindParam(':search', $like);
}

$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=report_" . date("Y-m-d_H-i-s") . ".xls");

echo "Full Name\tAge\tGender\tPhone\tEmail\tLocation\tItem\tPrice\tQty\tTotal\tDate\n";

foreach ($data as $row) {
    echo implode("\t", $row) . "\n";
}
exit;
?>
