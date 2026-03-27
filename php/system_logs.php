<?php
session_start();
include('dbconnect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    die("Unauthorized access");
}

try {
    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $dbh->prepare("
        SELECT sl.*, e.business_name
        FROM system_logs sl
        INNER JOIN establishment e ON sl.establishment_sid = e.establishment_sid
        ORDER BY sl.created_at DESC
    ");
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<table border="1">
    <thead>
        <tr>
            <th>Business Name</th>
            <th>Action</th>
            <th>Details</th>
            <th>IP Address</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($logs as $log): ?>
        <tr>
            <td><?= htmlspecialchars($log['business_name']) ?></td>
            <td><?= htmlspecialchars($log['action']) ?></td>
            <td><?= htmlspecialchars($log['details']) ?></td>
            <td><?= htmlspecialchars($log['ip_address']) ?></td>
            <td><?= htmlspecialchars($log['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
