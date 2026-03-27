<?php
session_start();
include('../dbconnect.php');

try {
    // ✅ Use PDO from dbconnect.php kung available, else create new connection
    $dbh = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS
    );
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ Current page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;

    $limit = 10; 
    $offset = ($page - 1) * $limit;

    // ✅ Count total logs
    $countQuery = "SELECT COUNT(*) FROM system_logs";
    $totalLogs = $dbh->query($countQuery)->fetchColumn();
    $totalPages = ceil($totalLogs / $limit);

    // ✅ Fetch logs (direct Manila time — no extra conversion)
    $query = "
        SELECT 
            DATE_FORMAT(sl.created_at, '%Y-%m-%d %H:%i:%s') AS created_at,
            sl.action,
            sl.details,
            e.business_name AS username
        FROM system_logs sl
        LEFT JOIN establishment e ON sl.user_id = e.establishment_sid
        ORDER BY sl.created_at DESC
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $dbh->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ✅ Display logs
    if (!empty($logs)) {
        foreach ($logs as $log) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($log['created_at']) . "</td>";
    echo "<td>" . htmlspecialchars($log['username'] ?? 'Unknown User') . "</td>";
    echo "<td>" . htmlspecialchars($log['action']) . "</td>";

    // Admin should NOT see full JSON details
    $details = $log['details'];

    if (preg_match('/^\{.*\}$/', trim($details))) {
        $decoded = json_decode($details, true);

        if ($decoded) {
            // Create human-friendly summary
            switch ($log['action']) {
                case "Customer Created":
                    $details = "Created a customer record";
                    break;
                case "Delete Customer":
                    $details = "Deleted a customer record";
                    break;
                case "Purchase Updated":
                    $details = "Updated a purchase transaction";
                    break;
                case "Purchase Created":
                    $details = "Created a purchase transaction";
                    break;
                default:
                    $details = $log['action'];
            }
        }
    }

    echo "<td>" . htmlspecialchars($details) . "</td>";
    echo "</tr>";
}

    } else {
        echo "<tr><td colspan='4' class='text-center'>No logs found.</td></tr>";
    }

    // ✅ Pagination
    echo "<tr><td colspan='4'>";
    echo "<nav aria-label='Logs pagination'><ul class='pagination justify-content-center'>";

    // Previous button
    $prevDisabled = ($page <= 1) ? "disabled" : "";
    echo "<li class='page-item $prevDisabled'>
            <a class='page-link' href='#' onclick='loadLogs(" . ($page - 1) . ")'>Previous</a>
          </li>";

    // Show only 3 pages at a time
    $start = max(1, $page - 1);
    $end   = min($totalPages, $start + 2);
    if ($end - $start < 2) {
        $start = max(1, $end - 2);
    }

    for ($i = $start; $i <= $end; $i++) {
        $active = ($i == $page) ? "active" : "";
        echo "<li class='page-item $active'>
                <a class='page-link' href='#' onclick='loadLogs($i)'>$i</a>
              </li>";
    }

    // Next button
    $nextDisabled = ($page >= $totalPages) ? "disabled" : "";
    echo "<li class='page-item $nextDisabled'>
            <a class='page-link' href='#' onclick='loadLogs(" . ($page + 1) . ")'>Next</a>
          </li>";

    echo "</ul></nav>";
    echo "</td></tr>";

} catch (PDOException $e) {
    echo "<tr><td colspan='4' class='text-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}
?>
