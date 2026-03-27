<?php
session_start();

if (!isset($_SESSION['business_name'])) {
    die("No business session found.");
}

$business_name = $_SESSION['business_name'];

// Encode para safe sa URL
$encodedBusiness = urlencode($business_name);

// QR will now contain the establishment name
$url = "http://localhost/borongan/public_registry.php?est=" . $encodedBusiness;

$qr = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($url);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Registration QR</title>
    <link rel="icon" type="image/png" href="fav.png" />
</head>
<body style="text-align:center;margin-top:80px;">
    <h2><?= htmlspecialchars($business_name) ?></h2>
    <p>Scan to Register Customer</p>
    <img id="qrImage" src="<?= $qr ?>" style="max-width:300px;"><br><br>
<br>
<div class="qr-buttons">
    <button class="modern-btn print-btn" onclick="window.print()">
        <i class="fas fa-print"></i> Print QR
    </button>

    <button class="modern-btn save-btn" id="saveQRBtn">
        <i class="fas fa-download"></i> Save QR
    </button>
</div>

<style>
    .qr-buttons {
    display: flex;
    gap: 12px;
    justify-content: center;
    margin-top: 20px;
}

.modern-btn {
    padding: 10px 18px;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.25s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.12);
}

/* Print Button - Gray */
.print-btn {
    background: #e0e0e0;
    color: #333;
}
.print-btn:hover {
    background: #cecece;
    transform: translateY(-2px);
}

/* Save Button - Purple */
.save-btn {
    background: #6c5ce7;
    color: white;
}
.save-btn:hover {
    background: #5847d6;
    transform: translateY(-2px);
}

</style>

</body>
</html>

<script>
document.getElementById("saveQRBtn").addEventListener("click", function () {
    const qrImage = document.getElementById("qrImage");
    const link = document.createElement("a");

    link.href = qrImage.src;
    link.download = "customer-registration-qr.png"; // filename
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});
</script>

