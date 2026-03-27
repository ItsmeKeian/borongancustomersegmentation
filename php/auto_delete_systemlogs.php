<?php
include('dbconnect.php'); // adjust the path based on your folder structure

// Delete logs older than 3 days based on the 'created_at' column
$query = "DELETE FROM system_logs WHERE created_at < NOW() - INTERVAL 2 DAY";

if (mysqli_query($conn, $query)) {
    $deleted = mysqli_affected_rows($conn);
    echo "✅ Successfully deleted $deleted old log(s).";
} else {
    echo "❌ Error deleting logs: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
