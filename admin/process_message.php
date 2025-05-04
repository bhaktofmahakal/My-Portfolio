<?php
require_once '../includes/config.php';

// Require login
require_login();

// Verify CSRF token
if (!isset($_GET['csrf_token']) || !isset($_SESSION['csrf_token']) || 
    $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token validation failed");
}

// Get action and message ID
if (!isset($_GET['action']) || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: messages.php');
    exit;
}

$action = sanitize_input($_GET['action']);
$message_id = (int)$_GET['id'];

// Verify message exists
$query = "SELECT id FROM messages WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $message_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    header('Location: messages.php');
    exit;
}

// Process action
switch ($action) {
    case 'toggle_status':
        // Get current status
        $query = "SELECT status FROM messages WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $message_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $message = mysqli_fetch_assoc($result);
        
        // Toggle status
        $new_status = $message['status'] === 'new' ? 'handled' : 'new';
        
        $query = "UPDATE messages SET status = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $new_status, $message_id);
        mysqli_stmt_execute($stmt);
        break;
        
    case 'delete':
        $query = "DELETE FROM messages WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $message_id);
        mysqli_stmt_execute($stmt);
        break;
        
    default:
        // Invalid action
        break;
}

// Redirect back
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'messages.php';
header('Location: ' . $referer);
exit;
?>