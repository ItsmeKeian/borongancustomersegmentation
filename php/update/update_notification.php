<?php
include '../dbconnect.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Establishment') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$establishment = $_SESSION['business_name'] ?? null;
if (!$establishment) {
    echo json_encode(['success' => false, 'message' => 'No establishment found']);
    exit;
}

// Mark all as read
if (isset($_POST['markAll']) && $_POST['markAll'] == true) {
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE establishment = ? AND is_read = 0");
    $stmt->bind_param("s", $establishment);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

// Mark all as unread
if (isset($_POST['markAllUnread']) && $_POST['markAllUnread'] == true) {
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 0 WHERE establishment = ? AND is_read = 1");
    $stmt->bind_param("s", $establishment);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

// Mark single notification as read
if (isset($_POST['id']) && !empty($_POST['id']) && !isset($_POST['markUnread']) && !isset($_POST['delete'])){
    $id = $_POST['id'];
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND establishment = ?");
    $stmt->bind_param("is", $id, $establishment);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

// Mark as unread
if(isset($_POST['id']) && isset($_POST['markUnread'])){
    $id = $_POST['id'];
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 0 WHERE id = ? AND establishment = ?");
    $stmt->bind_param("is", $id, $establishment);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

// Delete single notification
if(isset($_POST['id']) && isset($_POST['delete'])){
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM notifications WHERE id = ? AND establishment = ?");
    $stmt->bind_param("is", $id, $establishment);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

// Delete multiple notifications
if(isset($_POST['deleteMultiple']) && is_array($_POST['deleteMultiple']) && count($_POST['deleteMultiple']) > 0){
    $ids = $_POST['deleteMultiple']; // array of IDs

    // Make placeholders for the number of IDs
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    // Prepare statement with placeholders + establishment
    $stmt = $conn->prepare("DELETE FROM notifications WHERE id IN ($placeholders) AND establishment = ?");

    // Types string: 'i' for each ID + 's' for establishment
    $types = str_repeat('i', count($ids)) . 's';

    // mysqli bind_param needs references
    $params = [];
    foreach($ids as $key => $id){
        $params[$key] = &$ids[$key]; // reference required
    }
    $params[] = &$establishment;

    // Prepend types string
    array_unshift($params, $types);

    // Bind params dynamically
    call_user_func_array([$stmt, 'bind_param'], $params);

    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}
?>
