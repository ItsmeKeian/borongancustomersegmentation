<?php
require_once __DIR__ . '/php/dbconnect.php';
date_default_timezone_set('Asia/Manila');

// ✅ GET ESTABLISHMENT FROM QR URL
$establishment = $_GET['est'] ?? null;

if (!$establishment) {
    die("<h2 style='color:#2F539B;text-align:center;'>❌ Invalid QR Code</h2>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullName   = $_POST['fullname'] ?? '';
    $email      = $_POST['email'] ?? '';
    $age        = $_POST['age'] ?? '';
    $phone      = $_POST['phone'] ?? '';
    $gender     = $_POST['gender'] ?? '';
    $segment    = $_POST['segment'] ?? '';
    $location   = $_POST['location'] ?? '';
    $occupation = $_POST['occupation'] ?? '';
    $income     = $_POST['income'] ?? '';
    $education  = $_POST['education'] ?? '';
    $dateCreated = date("Y-m-d H:i:s");

    if (
        empty($fullName) || empty($age) || empty($gender) ||
        empty($location) || empty($email) || empty($phone) ||
        empty($segment) || empty($occupation) || empty($income) || empty($education)
    ) {
        echo "<script>
            Swal.fire('Incomplete!', 'Please complete all required fields.', 'warning');
        </script>";
        exit;
    }

    try {
        $dbh = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        // ✅ CHECK IF EMAIL ALREADY EXISTS FOR THIS ESTABLISHMENT
        $check = $dbh->prepare("
            SELECT id FROM customer 
            WHERE email = :email AND establishment = :establishment
            LIMIT 1
        ");
        $check->bindValue(':email', $email);
        $check->bindValue(':establishment', $establishment);
        $check->execute();

        if ($check->rowCount() > 0) {
           // ✅ SWEETALERT SUCCESS + AUTO CLOSE AFTER 3 SECONDS
            echo "
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful!',
                    text: 'This page will close automatically...',
                    timer: 3000,
                    showConfirmButton: false
                });

                setTimeout(function(){
                    window.close();
                }, 3000);
            </script>";
            exit;

        }

        // ✅ INSERT CUSTOMER
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

        $stmt->execute();

        // ✅ SWEETALERT SUCCESS
        echo "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Registration Successful!',
                text: 'You may now close this page.',
                showConfirmButton: true
            });
        </script>";
        exit;

    } catch (PDOException $e) {
        echo "<script>
            Swal.fire('Database Error', '".addslashes($e->getMessage())."', 'error');
        </script>";
        exit;
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Registration</title>
    <link rel="icon" type="image/png" href="fav.png" />
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #2c3e50, #2F539B );
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .form-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(14px);
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            color: white;
        }

        .form-card h2 {
            text-align: center;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .form-card p {
            text-align: center;
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 22px;
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-group label {
            font-size: 13px;
            display: block;
            margin-bottom: 4px;
            opacity: 0.85;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: none;
            outline: none;
            font-size: 14px;
        }

        .form-group input::placeholder {
            color: #999;
        }

        .submit-btn {
            margin-top: 18px;
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, #6c5ce7, #a29bfe);
            cursor: pointer;
            transition: 0.25s ease;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.25);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            opacity: 0.95;
        }

        .footer-note {
            text-align: center;
            font-size: 11px;
            opacity: 0.8;
            margin-top: 16px;
        }

        @media (max-width: 480px) {
            .form-card {
                padding: 22px;
            }
        }
    </style>
</head>
<body>

<div class="form-card">
    <h2><?= htmlspecialchars($establishment) ?></h2>
    <p>Customer Registration</p>

    <form method="POST">

    <div class="form-group">
        <label>Full Name</label>
        <input name="fullname" placeholder="Enter your full name" required>
    </div>

    <div class="form-group">
        <label>Email Address</label>
        <input name="email" type="email" placeholder="Enter your email" required>
    </div>

    <div class="form-group">
        <label>Age</label>
        <input name="age" type="number" placeholder="Enter your age" required>
    </div>

    <div class="form-group">
        <label>Phone Number</label>
        <input name="phone" placeholder="Enter your phone number" required>
    </div>

    <div class="form-group">
        <label>Gender</label>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option>Male</option>
            <option>Female</option>
        </select>
    </div>

    <div class="form-group">
        <label>Segment</label>
        <select name="segment" id="segment" required>
            <option value="">Loading segments...</option>
        </select>
    </div>

    <div class="form-group">
        <label>Occupation</label>
        <input name="occupation" placeholder="Enter your occupation" required>
    </div>

    <div class="form-group">
        <label>Estimated Income</label>
        <input type="number" name="income" placeholder="Enter estimated income" required>
    </div>

    <div class="form-group">
        <label>Education</label>
        <select name="education" required>
            <option value="">Select</option>
            <option>Elementary</option>
            <option>High School</option>
            <option>College</option>
            <option>Vocational</option>
            <option>Post Graduate</option>
        </select>
    </div>

    <div class="form-group">
        <label>Location</label>
        <input name="location" placeholder="Enter your location" required>
    </div>

    <button class="submit-btn" type="submit">
        ✅ Submit Registration
    </button>

</form>


    <div class="footer-note">
        Powered by Borongan Customer Segmentation
    </div>
</div>

</body>
</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
$(document).ready(function () {

    const establishment = "<?= htmlspecialchars($establishment) ?>";

    function loadPublicSegments() {
        $.getJSON("php/get/get_public_segments.php?est=" + encodeURIComponent(establishment), function(response) {

            const $segment = $("#segment");
            $segment.empty().append('<option value="">Select type</option>');

            if (response.status === 1) {
                response.segments.forEach(seg => {
                    const option = $('<option>', {
                        value: seg.name,
                        text: seg.name
                    });
                    $segment.append(option);
                });
            } else {
                alert("⚠️ Failed to load segments");
            }
        });
    }

    // ✅ Auto-load when page opens
    loadPublicSegments();

});
</script>
