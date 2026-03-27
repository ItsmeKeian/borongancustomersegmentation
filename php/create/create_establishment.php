<?php
session_start();
include('../dbconnect.php');  

// ✅ Only Admin can create establishments
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    echo json_encode(["status" => 0, "message" => "Unauthorized"]);
    exit();
}

try {
    $ab = trim($_POST['a'] ?? ''); // business_name
    $bb = trim($_POST['b'] ?? ''); // business_type
    $cb = trim($_POST['c'] ?? ''); // owners_name
    $db = trim($_POST['d'] ?? ''); // email
    $eb = trim($_POST['e'] ?? ''); // contact
    $fb = trim($_POST['f'] ?? ''); // address
    $gb = trim($_POST['g'] ?? ''); // password
    $hb = trim($_POST['h'] ?? ''); // confirm password
    $ib = trim($_POST['i'] ?? ''); // date_time (from form)

    // ✅ Check required fields
   if (
    empty($ab) || 
    empty($cb) || 
    empty($db) || 
    empty($eb) || 
    empty($fb) || 
    empty($gb) || 
    empty($hb) || 
    empty($ib) || 
    $bb === "Select type"
) {
    echo json_encode(['status' => 0, 'message' => 'Please complete all required fields']);
    exit;
}

    // ✅ Check password match
    if ($gb !== $hb) {
        echo json_encode(['status' => 0, 'message' => 'Passwords do not match']);
        exit;
    }

    // ✅ Hash password
    $hashedPassword = password_hash($gb, PASSWORD_DEFAULT);

    // ✅ Database connection
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ Check if email already exists
    $check = $dbh->prepare("SELECT COUNT(*) FROM establishment WHERE email = :email");
    $check->execute([':email' => $db]);
    if ($check->fetchColumn() > 0) {
        echo json_encode(['status' => 0, 'message' => 'Email is already registered']);
        exit;
    }

    // ✅ Insert new establishment
    $qry = "INSERT INTO `establishment`
            (`business_name`, `business_type`, `owners_name`, `email`, `contact`, `address`, `date_time`, `password`, `confirmpassword`) 
            VALUES (:a, :b, :c, :d, :e, :f, :i, :g, :h)";
    
    $stmt = $dbh->prepare($qry);
    $stmt->bindParam(":a", $ab);
    $stmt->bindParam(":b", $bb);
    $stmt->bindParam(":c", $cb);
    $stmt->bindParam(":d", $db);
    $stmt->bindParam(":e", $eb);
    $stmt->bindParam(":f", $fb);
    $stmt->bindParam(":i", $ib); 
    $stmt->bindParam(":g", $hashedPassword);
    $stmt->bindParam(":h", $hashedPassword);

    if ($stmt->execute()) {
        echo json_encode(['status' => 1, 'message' => 'Establishment created successfully.']);
    } else {
        echo json_encode(['status' => 0, 'message' => 'Failed to create establishment.']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 0, 'message' => 'Database error: ' . $e->getMessage()]); 
}
