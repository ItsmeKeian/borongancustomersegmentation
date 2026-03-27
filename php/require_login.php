<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_ajax_request(): bool {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

// session expiry
if (!empty($_SESSION['expires_at']) && time() > $_SESSION['expires_at']) {
    session_unset();
    session_destroy();
    if (is_ajax_request()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['status' => 0, 'msg' => 'Session expired']);
    } else {
        header("Location: /index.php?msg=Session expired, please log in again");
    }
    exit;
} else {
    // refresh session expiry (extend 1 hour on activity)
    if (!empty($_SESSION['user_id'])) {
        $_SESSION['expires_at'] = time() + 3600;
    }
}

// must be logged in
if (empty($_SESSION['user_id']) || empty($_SESSION['role'])) {
    if (is_ajax_request()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['status' => 0, 'msg' => 'Unauthorized']);
    } else {
        header("Location: /index.php");
    }
    exit;
}

// role restriction
function require_role(string $role) {
    if ($_SESSION['role'] !== $role) {
        if (is_ajax_request()) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['status' => 0, 'msg' => 'Forbidden: insufficient role']);
        } else {
            header("HTTP/1.1 403 Forbidden");
            echo "❌ Forbidden: You don't have permission to access this page.";
        }
        exit;
    }
}
