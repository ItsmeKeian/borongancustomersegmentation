<?php
session_start();
include('../dbconnect.php');

try {
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ Pagination inputs
    $page  = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;
    $offset = ($page - 1) * $limit;

    // ✅ Get total count
    $countQry = "SELECT COUNT(*) FROM establishment";
    $total = $dbh->query($countQry)->fetchColumn();

    // ✅ Query with computed status + pagination
    $qry = "SELECT establishment_sid, business_name, business_type, date_time, owners_name, email, contact, address, 
                   status, last_login,
                   CASE 
                       WHEN last_login IS NOT NULL AND last_login >= NOW() - INTERVAL 7 DAY THEN 'Active'
                       ELSE 'Inactive'
                   END AS computed_status
            FROM establishment
            ORDER BY establishment_sid DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $dbh->prepare($qry);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ✅ Update DB status if needed
    foreach ($result as $row) {
        $newStatus = $row['computed_status'];
        if ($row['status'] !== $newStatus) {
            $upd = $dbh->prepare("UPDATE establishment SET status = :status WHERE establishment_sid = :id");
            $upd->execute([':status' => $newStatus, ':id' => $row['establishment_sid']]);
        }
    }

    echo json_encode([
        "status" => 1,
        "data"   => $result,
        "total"  => $total
    ]);

} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => "Database Error: " . $e->getMessage()]);
}

$dbh = null;
?>
