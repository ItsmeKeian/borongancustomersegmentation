<?php
include 'dbconnect.php';

echo "✅ PHP time: " . date('Y-m-d H:i:s') . "<br>";

$res = $conn->query("SELECT NOW() AS mysql_time, @@session.time_zone AS tz");
$row = $res->fetch_assoc();
echo "✅ MySQLi time: " . $row['mysql_time'] . " (timezone: " . $row['tz'] . ")<br>";

if ($pdo) {
    $stmt = $pdo->query("SELECT NOW() AS mysql_time, @@session.time_zone AS tz");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ PDO time: " . $row['mysql_time'] . " (timezone: " . $row['tz'] . ")";
}
?>
