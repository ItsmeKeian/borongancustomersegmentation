<?php
session_start();
require_once __DIR__ . '/dbconnect.php';
header('Content-Type: application/json; charset=utf-8');

date_default_timezone_set('Asia/Manila'); // ✅ Always Manila time

try {
    $username   = $_POST['un'] ?? '';
    $password   = $_POST['pw'] ?? '';
    $loginType  = $_POST['loginType'] ?? '';

    if (empty($username) || empty($password) || empty($loginType)) {
        echo json_encode(['status' => 0, 'msg' => 'Missing fields']);
        exit;
    }

    $dbh = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // ============================
    // Helper Functions
    // ============================
    function is_locked_out($dbh, $username, $estName) {
        $stmt = $dbh->prepare("SELECT attempts, last_attempt 
                               FROM failed_logins 
                               WHERE username = :un AND establishment_name = :est 
                               LIMIT 1");
        $stmt->execute([':un' => $username, ':est' => $estName]);
        $row = $stmt->fetch();

        if ($row) {
            if ($row['attempts'] >= 5 && strtotime($row['last_attempt']) > strtotime("-5 minutes")) {
                return true;
            }
            if (strtotime($row['last_attempt']) <= strtotime("-5 minutes") && $row['attempts'] > 0) {
                $reset = $dbh->prepare("UPDATE failed_logins 
                                        SET attempts = 0 
                                        WHERE username = :un AND establishment_name = :est");
                $reset->execute([':un' => $username, ':est' => $estName]);
            }
        }
        return false;
    }

    function record_failed($dbh, $username, $estName) {
        $now = date("Y-m-d H:i:s"); // ✅ Manila time
        $stmt = $dbh->prepare("INSERT INTO failed_logins (username, establishment_name, attempts, last_attempt) 
                               VALUES (:un, :est, 1, :last_attempt)
                               ON DUPLICATE KEY UPDATE 
                                   attempts = attempts + 1,
                                   last_attempt = :last_attempt");
        $stmt->execute([':un' => $username, ':est' => $estName, ':last_attempt' => $now]);
    }

    function reset_failed($dbh, $username, $estName) {
        $now = date("Y-m-d H:i:s"); // ✅ Manila time
        $stmt = $dbh->prepare("UPDATE failed_logins 
                               SET attempts = 0, last_attempt = :last_attempt
                               WHERE username = :un AND establishment_name = :est");
        $stmt->execute([':un' => $username, ':est' => $estName, ':last_attempt' => $now]);
    }

   
        // ============================
        // ADMIN LOGIN (with auto-hash upgrade)
        // ============================
        if ($loginType === 'admin') {
            $stmt = $dbh->prepare("SELECT acc_id, username, password, role 
                                   FROM account 
                                   WHERE username = :un 
                                   LIMIT 1");
            $stmt->execute([':un' => $username]);
            $data = $stmt->fetch();
        
            if ($data) {
                $dbPassword = $data['password'];
                $accId = $data['acc_id'];
                $isVerified = false;
        
                // 1. Check if the password matches the hash
                if (!empty($dbPassword) && password_verify($password, $dbPassword)) {
                    $isVerified = true;
                }
                // 2. Or if DB password is plain text and matches directly
                elseif ($password === $dbPassword) {
                    $isVerified = true;
        
                    // 🔒 Auto-upgrade: hash and save new password securely
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    $upd = $dbh->prepare("UPDATE account SET password = :hash WHERE acc_id = :id LIMIT 1");
                    $upd->execute([':hash' => $newHash, ':id' => $accId]);
                }
        
                // 3 If verified (either case)
                if ($isVerified) {
                    session_regenerate_id(true);
        
                    $_SESSION['user_id']   = $data['acc_id'];
                    $_SESSION['username']  = $data['username'];
                    $_SESSION['role']      = $data['role'];
                    $_SESSION['expires_at']= time() + 3600;
        
                    echo json_encode(['status' => 1, 'role' => $data['role']]);
                    exit;
                }
            }
        
            // if we reach here, credentials are wrong
            echo json_encode(['status' => 0, 'msg' => 'Invalid admin credentials']);
            exit;
        }


    // ============================
    // ESTABLISHMENT LOGIN
    // ============================
    if ($loginType === 'establishment') {
    $stmt = $dbh->prepare("SELECT establishment_sid, business_name, email, password 
                           FROM establishment 
                           WHERE email = :un 
                           LIMIT 1");
    $stmt->execute([':un' => $username]);
    $data = $stmt->fetch();

    $estName = $data['business_name'] ?? 'Unknown Establishment';

    if (is_locked_out($dbh, $username, $estName)) {
        echo json_encode(['status' => 0, 'msg' => 'Too many failed attempts. Please wait 5 minutes.']);
        exit;
    }

    if (!$data) {
        record_failed($dbh, $username, $estName);
        echo json_encode(['status' => 0, 'msg' => 'Invalid establishment credentials']);
        exit;
    }

    $dbHash = $data['password'];
    $estId  = $data['establishment_sid'];
    $ok     = false;

    if (!empty($dbHash) && password_verify($password, $dbHash)) {
        $ok = true;
    } elseif ($dbHash === $password) {
        $ok = true;
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $upd = $dbh->prepare("UPDATE establishment SET password = :hash WHERE establishment_sid = :id LIMIT 1");
        $upd->execute([':hash' => $newHash, ':id' => $estId]);
    }

    if ($ok) {
        reset_failed($dbh, $username, $estName);
        session_regenerate_id(true);

        // -------------------------------
        // ⭐ THE CORRECT SESSION VALUES
        // -------------------------------
        $_SESSION['user_id']            = $estId;                    // optional
        $_SESSION['establishment_sid']  = $estId;                    // REQUIRED for chatbot
        $_SESSION['username']           = $data['email'];
        $_SESSION['role']               = 'Establishment';
        $_SESSION['business_name']      = $data['business_name'];
        $_SESSION['expires_at']         = time() + 3600;

        // Save last login
        $now = date("Y-m-d H:i:s"); 
        $upd = $dbh->prepare("UPDATE establishment SET last_login = :last_login WHERE establishment_sid = :id");
        $upd->execute([':last_login' => $now, ':id' => $estId]);

        include_once 'fetch_logs.php';
        logAction($dbh, $estId, 'Login', 'User logged in successfully.');

        echo json_encode(['status' => 1, 'role' => 'Establishment']);
    } else {
        record_failed($dbh, $username, $estName);
        echo json_encode(['status' => 0, 'msg' => 'Invalid establishment credentials']);
    }
    exit;
}

    echo json_encode(['status' => 0, 'msg' => 'Invalid login type']);

} catch (PDOException $e) {
    echo json_encode(['status' => 0, 'msg' => 'Database Error: ' . $e->getMessage()]);
}
