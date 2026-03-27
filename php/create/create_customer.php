<?php
session_start();
include('../dbconnect.php');
include_once '../fetch_logs.php'; // Logging function

// ----------------------------------------
// 1. SECURITY CHECK
// ----------------------------------------
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Establishment") {
    echo json_encode(["status" => 0, "message" => "Unauthorized access"]);
    exit();
}

$establishment = $_SESSION['business_name'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

if (!$establishment) {
    echo json_encode(["status" => 0, "message" => "No establishment found in session"]);
    exit();
}

// ----------------------------------------
// 2. GET POST DATA
// ----------------------------------------
$fullName   = $_POST['a'] ?? '';
$age        = $_POST['b'] ?? '';
$gender     = $_POST['c'] ?? '';
$location   = $_POST['d'] ?? '';
$email      = $_POST['e'] ?? '';
$phone      = $_POST['f'] ?? '';
$segment    = $_POST['g'] ?? '';
$dateCreated = $_POST['i'] ?? '';
$occupation = $_POST['h'] ?? '';
$income     = $_POST['j'] ?? '';
$education  = $_POST['k'] ?? '';

// ----------------------------------------
// 3. VALIDATION
// ----------------------------------------
if (empty($fullName) || empty($age) || empty($gender) || empty($location)) {
    echo json_encode(['status' => 0, 'message' => 'Please complete required fields']);
    exit;
}

try {

    // ----------------------------------------
    // 4. DB CONNECTION
    // ----------------------------------------
    $dbh = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // ----------------------------------------
    // 5. INSERT QUERY
    // ----------------------------------------
    $sql = "
        INSERT INTO customer 
        (full_name, age, gender, location, email, phone, segment, occupation, estimated_income, education, establishment, created_at)
        VALUES 
        (:fullName, :age, :gender, :location, :email, :phone, :segment, :occupation, :income, :education, :establishment, :createdAt)
    ";

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':fullName', $fullName);
    $stmt->bindValue(':age', $age);
    $stmt->bindValue(':gender', $gender);
    $stmt->bindValue(':location', $location);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':phone', $phone);
    $stmt->bindValue(':segment', $segment);
    $stmt->bindValue(':occupation', $occupation);
    $stmt->bindValue(':income', $income);
    $stmt->bindValue(':education', $education);
    $stmt->bindValue(':establishment', $establishment);
    $stmt->bindValue(':createdAt', $dateCreated);

    // ----------------------------------------
    // 6. EXECUTE INSERT
    // ----------------------------------------
    if ($stmt->execute()) {

        // Get the customer ID inserted
        $customerId = $dbh->lastInsertId();

        // ----------------------------------------
        // LOGGING: FULL CUSTOMER DATA
        // ----------------------------------------
        if ($userId) {

            $logDetails = [
               
                "full_name" => $fullName,
                "age" => $age,
                "gender" => $gender,
                "location" => $location,
                "email" => $email,
                "phone" => $phone,
                "segment" => $segment,
                "occupation" => $occupation,
                "estimated_income" => $income,
                "education" => $education,
                "created_at" => $dateCreated
            ];

            // With establishment name → REQUIRED for establishment logs
            logAction(
                $dbh,
                $userId,
                'Customer Created',
                $logDetails,
                $establishment
            );
        }

        echo json_encode(['status' => 1, 'message' => 'Customer created successfully']);
    } else {
        echo json_encode(['status' => 0, 'message' => 'Failed to save record']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 0, 'message' => 'Database error: ' . $e->getMessage()]);
}

$dbh = null;
?>
