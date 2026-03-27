<?php
function logAction(PDO $dbh, $userId, string $action, $details = '', $establishment = null) 
{
    try {
        date_default_timezone_set('Asia/Manila');
        $now = date("Y-m-d H:i:s");

        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

        if (is_array($details)) {
            $details = json_encode($details, JSON_UNESCAPED_UNICODE);
        }

        $sql = "INSERT INTO system_logs 
                (user_id, establishment_name, action, details, ip_address, created_at)
                VALUES (:user_id, :est, :action, :details, :ip, :created_at)";
        
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            ':user_id'   => $userId,
            ':est'       => $establishment,
            ':action'    => $action,
            ':details'   => $details,
            ':ip'        => $ip,
            ':created_at'=> $now
        ]);

    } catch (PDOException $e) {
        error_log("Log insert failed: " . $e->getMessage());
    }
}
?>

