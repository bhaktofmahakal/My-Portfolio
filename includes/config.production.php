<?php
// Database configuration - REPLACE THESE VALUES with your hosting provider's details
define('DB_HOST', 'localhost'); // Usually 'localhost' on most hosting providers
define('DB_USER', 'your_db_username'); // Replace with your database username
define('DB_PASS', 'your_db_password'); // Replace with your database password
define('DB_NAME', 'your_db_name'); // Replace with your database name

// Establish database connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Session configuration
session_start();

// CSRF Protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Function to sanitize input data
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if ($conn) {
        $data = mysqli_real_escape_string($conn, $data);
    }
    return $data;
}

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Function to redirect if not logged in
function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

// Function to verify CSRF token
function verify_csrf_token() {
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
        $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }
}

// Function to generate a new CSRF token
function refresh_csrf_token() {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}

// Rate limiting function
function check_rate_limit($ip_address, $action = 'contact_form', $limit = 5, $time_period = 3600) {
    global $conn;
    
    // Clean up old entries
    $query = "DELETE FROM rate_limits WHERE timestamp < (NOW() - INTERVAL $time_period SECOND)";
    mysqli_query($conn, $query);
    
    // Count attempts
    $query = "SELECT COUNT(*) as count FROM rate_limits WHERE ip_address = ? AND action = ? AND timestamp > (NOW() - INTERVAL $time_period SECOND)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $ip_address, $action);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    if ($row['count'] >= $limit) {
        return false; // Rate limit exceeded
    }
    
    // Record this attempt
    $query = "INSERT INTO rate_limits (ip_address, action, timestamp) VALUES (?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $ip_address, $action);
    mysqli_stmt_execute($stmt);
    
    return true; // Within rate limit
}
?>