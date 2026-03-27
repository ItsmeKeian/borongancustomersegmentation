<?php
include("../dbconnect.php");
session_start();

$establishment = $_SESSION['business_name'] ?? null;

if (!$establishment) {
    echo json_encode(["status" => 0, "message" => "Unauthorized"]);
    exit;
}

try {
    $dbh = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $segments = [];

    /*
    |--------------------------------------------------------------------------
    | 1) MANUAL SEGMENTS (unchanged)
    |--------------------------------------------------------------------------
    */
    $sql = "
        SELECT 
            s.id,
            s.name,
            s.description,
            s.criteria,
            COUNT(DISTINCT c.customer_sid) AS customer_count,
            IFNULL(SUM(p.total),0) AS total_revenue,
            CASE 
                WHEN COUNT(DISTINCT c.customer_sid) > 0
                THEN ROUND(SUM(p.total) / COUNT(DISTINCT c.customer_sid), 2)
                ELSE 0
            END AS avg_spent_per_customer
        FROM segments s
        LEFT JOIN customer c 
            ON c.segment = s.name
           AND c.establishment = s.establishment
        LEFT JOIN purchased p
            ON p.customer_sid = c.customer_sid
           AND p.establishment = s.establishment
        WHERE s.establishment = :establishment
        GROUP BY s.id
        ORDER BY s.created_at DESC
    ";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":establishment", $establishment);
    $stmt->execute();
    $segments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /*
    |--------------------------------------------------------------------------
    | 2) AUTO SEGMENTS (COMPUTED — THIS IS THE FIX)
    |--------------------------------------------------------------------------
    */

    $autoSegments = [

        "LOYAL CUSTOMERS" => "
            SELECT COUNT(*)
            FROM (
                SELECT p.customer_sid
                FROM purchased p
                WHERE p.establishment = :establishment
                AND p.date_purchase >= DATE_SUB(NOW(), INTERVAL 2 MONTH)
                AND p.total >= 10
                GROUP BY p.customer_sid
                HAVING COUNT(*) >= 16
            ) AS loyal_customers
        ",


        "STUDENTS" => "
            SELECT COUNT(*) FROM customer
            WHERE establishment = :establishment
            AND education IN (
                'Elementary',
                'High School',
                'College',
                'Vocational',
                'Post Graduate'
            )
        ",

        "PROFESSIONALS" => "
            SELECT COUNT(*) FROM customer
            WHERE establishment = :establishment
            AND occupation IS NOT NULL
            AND occupation <> ''
            AND LOWER(occupation) NOT IN ('student', 'none', 'n/a')
        ",

        "KIDS / TEENS" => "
            SELECT COUNT(*) FROM customer
            WHERE establishment = :establishment
              AND age < 18
        ",

        "YOUNG ADULTS" => "
            SELECT COUNT(*) FROM customer
            WHERE establishment = :establishment
              AND age BETWEEN 18 AND 30
        ",

        "ADULTS" => "
            SELECT COUNT(*) FROM customer
            WHERE establishment = :establishment
              AND age BETWEEN 31 AND 59
        ",

        "SENIORS" => "
            SELECT COUNT(*) FROM customer
            WHERE establishment = :establishment
              AND age >= 60
        "
    ];

    $descriptions = [
        "LOYAL CUSTOMERS" => "High-frequency, high-value customers",
        "STUDENTS" => "Customers currently in education",
        "PROFESSIONALS" => "Customers with active occupations",
        "KIDS / TEENS" => "Customers below 18",
        "YOUNG ADULTS" => "Customers aged 18–30",
        "ADULTS" => "Customers aged 31–59",
        "SENIORS" => "Customers aged 60+",
        
    ];

    foreach ($autoSegments as $name => $countSQL) {
        $stmt = $dbh->prepare($countSQL);
        $stmt->bindParam(":establishment", $establishment);
        $stmt->execute();

        $count = (int) $stmt->fetchColumn();

        $segments[] = [
            "id" => 0,
            "name" => $name,
            "description" => $descriptions[$name],
            "criteria" => "AUTO",
            "customer_count" => $count,
            "total_revenue" => 0,
            "avg_spent_per_customer" => 0
        ];
    }

    echo json_encode(["status" => 1, "segments" => $segments]);

} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => $e->getMessage()]);
}
