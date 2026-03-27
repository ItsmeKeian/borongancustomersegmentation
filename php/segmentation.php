<?php
session_start();
include('dbconnect.php');

try {
    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Establishment filter
    $establishment = "";
    if (isset($_SESSION['role']) && $_SESSION['role'] === "Establishment") {
        $establishment = $_SESSION['business_name'];
    }

    $segments = ['students', 'professionals', 'families'];
    $result = [];

    foreach ($segments as $seg) {
        $params = [':segment' => $seg];
        $where = "c.segment = :segment";

        if ($establishment) {
            $where .= " AND c.establishment = :est";
            $params[':est'] = $establishment;
        }

        // Count customers
        $qry = "SELECT COUNT(*) as cnt 
                FROM customer c
                WHERE $where";
        $stmt = $dbh->prepare($qry);
        $stmt->execute($params);
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];

        // Revenue
        $qry = "SELECT SUM(p.total) as revenue
                FROM purchased p
                INNER JOIN customer c 
                    ON c.full_name = p.full_name AND c.establishment = p.establishment
                WHERE $where";
        $stmt = $dbh->prepare($qry);
        $stmt->execute($params);
        $revenue = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'] ?? 0;

        $result[$seg] = [
            'count' => (int)$count,
            'revenue' => (float)$revenue
        ];
    }

    // Totals
    $total_customers = $result['students']['count'] + $result['professionals']['count'] + $result['families']['count'];
    $total_revenue = $result['students']['revenue'] + $result['professionals']['revenue'] + $result['families']['revenue'];

    echo json_encode([
        'status' => 1,
        'students' => $result['students'],
        'professionals' => $result['professionals'],
        'families' => $result['families'],
        'totals' => [
            'customers' => $total_customers ?: 1,
            'revenue' => $total_revenue ?: 1
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode(['status' => 0, 'message' => $e->getMessage()]);
}
?>
