<?php
session_start();
include('../dbconnect.php');
include_once '../fetch_logs.php';
header('Content-Type: application/json');

// ----------------------------------------
// 1. SECURITY CHECK
// ----------------------------------------
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    echo json_encode(["status" => 0, "message" => "Unauthorized access"]);
    exit();
}

$userId = $_SESSION['user_id'] ?? null;
$establishment = $_SESSION['business_name'] ?? null;

if (!$establishment) {
    echo json_encode(["status" => 0, "message" => "Establishment missing"]);
    exit();
}

try {
    // ----------------------------------------
    // 2. GET POST DATA
    // ----------------------------------------
    $id          = intval($_POST['id']);
    $newData = [
        "full_name"        => $_POST['full_name'] ?? '',
        "age"              => $_POST['age'] ?? '',
        "gender"           => $_POST['gender'] ?? '',
        "location"         => $_POST['location'] ?? '',
        "email"            => $_POST['email'] ?? '',
        "phone"            => $_POST['phone'] ?? '',
        "segment"          => $_POST['segment'] ?? '',
        "occupation"       => $_POST['occupation'] ?? '',
        "estimated_income" => $_POST['income'] ?? '',
        "education"        => $_POST['education'] ?? ''
       
    ];

    // ----------------------------------------
    // 3. DATABASE
    // ----------------------------------------
    $dbh = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // ----------------------------------------
    // 4. GET FULL OLD DATA
    // ----------------------------------------
    $getOld = $dbh->prepare("SELECT * FROM customer WHERE customer_sid = :id");
    $getOld->bindParam(":id", $id, PDO::PARAM_INT);
    $getOld->execute();

    $oldData = $getOld->fetch(PDO::FETCH_ASSOC);
    if (!$oldData) {
        echo json_encode(["status" => 0, "message" => "Record not found"]);
        exit();
    }

    // ----------------------------------------
    // 5. DETECT CHANGES
    // ----------------------------------------
    $changes = [];
    foreach ($newData as $key => $newValue) {
        $oldValue = $oldData[$key] ?? null;

        if ($newValue != $oldValue) {
            $changes[$key] = [
                "old" => $oldValue,
                "new" => $newValue
            ];
        }
    }

    // If no field was changed, do not log an update
    if (empty($changes)) {
        echo json_encode(["status" => 1, "message" => "No changes detected"]);
        exit();
    }

    // ----------------------------------------
    // 6. EXECUTE UPDATE
    // ----------------------------------------
    $stmt = $dbh->prepare("
        UPDATE customer 
        SET full_name = :full_name,
            age = :age,
            gender = :gender,
            location = :location,
            email = :email,
            phone = :phone,
            segment = :segment,
            occupation = :occupation,
            estimated_income = :income,
            education = :education,
            establishment = :establishment
          
        WHERE customer_sid = :id
    ");

    $stmt->execute([
        ":full_name"    => $newData["full_name"],
        ":age"          => $newData["age"],
        ":gender"       => $newData["gender"],
        ":location"     => $newData["location"],
        ":email"        => $newData["email"],
        ":phone"        => $newData["phone"],
        ":segment"      => $newData["segment"],
        ":occupation"   => $newData["occupation"],
        ":income"       => $newData["estimated_income"],
        ":education"    => $newData["education"],
        ":establishment" => $establishment,
       
        ":id"           => $id
    ]);

    // ----------------------------------------
    // 7. LOG FULL DETAILS AS JSON
    // ----------------------------------------
    $logJSON = json_encode([
        "customer_id" => $id,
        "before"      => $oldData,
        "after"       => $newData,
        "changes"     => $changes
    ], JSON_UNESCAPED_UNICODE);

    logAction($dbh, $userId, "Update Customer", $logJSON);

    echo json_encode(["status" => 1, "message" => "Record updated successfully"]);

} catch (PDOException $e) {
    echo json_encode(["status" => 0, "message" => "Database error: " . $e->getMessage()]);
}
?>
