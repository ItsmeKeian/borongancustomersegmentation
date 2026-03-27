<?php
session_start();
session_unset();
session_destroy();
header("Location: index.php");
exit();
?>


<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    header("Location: index.php");
    exit();
}
?>