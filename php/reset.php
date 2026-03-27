<?php
require_once __DIR__ . '/dbconnect.php';
$dbh = new PDO(
    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
    DB_USER,
    DB_PASS,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$newPass = "1234"; // bagong pansamantalang password
$hash = password_hash($newPass, PASSWORD_DEFAULT);

$stmt = $dbh->prepare("UPDATE establishment SET password = :pw WHERE establishment_sid = 3");
$stmt->execute([':pw' => $hash]);

echo "Password reset ";
?>
