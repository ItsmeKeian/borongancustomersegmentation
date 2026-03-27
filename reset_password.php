<?php
include('php/dbconnect.php');

// Validate token
$token = $_GET['token'] ?? '';

if (empty($token)) {
    die("Invalid token (missing).");
}

$hashedToken = hash('sha256', $token);

$stmt = $pdo->prepare("
    SELECT email, expires_at
    FROM password_resets
    WHERE token = :token
");
$stmt->execute([':token' => $hashedToken]);
$resetData = $stmt->fetch(PDO::FETCH_ASSOC);

// Debugging output
if (!$resetData) {
    die("Invalid token or token not found in database.");
}

// Check expiration
$currentDate = date('Y-m-d H:i:s');
if ($resetData['expires_at'] < $currentDate) {
    die("Token expired. Please request a new password reset link.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borongan City Customer Segmentation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="fav.png" />
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-header bg-primary text-white">Reset Your Password</div>
        <div class="card-body">
            <form id="resetPasswordForm">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <div class="mb-3">
                    <label for="newPassword" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Reset Password</button>
            </form>
            <div id="resetMessage" class="mt-3 text-center"></div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("#resetPasswordForm").submit(function(e) {
        e.preventDefault();

        $.post("php/update_password.php", $(this).serialize(), function(response) {
            console.log(response); //  Debugging line

            if (response.status === 1) {
                $("#resetMessage").html('<div class="alert alert-success">' + response.message + '</div>');
                $("#resetPasswordForm")[0].reset();
            } else {
                $("#resetMessage").html('<div class="alert alert-danger">' + response.message + '</div>');
            }
        }, "json")
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("AJAX Error:", textStatus, errorThrown, jqXHR.responseText); // Debug any AJAX errors
        });
    });
});
</script>
</body>
</html>
