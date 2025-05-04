<?php
// Allow requests from your Vercel domain
header("Access-Control-Allow-Origin: *"); // Allow requests from any domain for testing
// Later you can restrict this to your specific domains:
header("Access-Control-Allow-Origin: https://portfolio-2-e4ig.vercel.app");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'includes/config.php';

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Check for rate limiting
$ip_address = $_SERVER['REMOTE_ADDR'];
if (!check_rate_limit($ip_address, 'contact_form', 5, 3600)) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many requests. Please try again later.']);
    exit;
}

// Get and sanitize form data
$name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
$email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
$subject = isset($_POST['subject']) ? sanitize_input($_POST['subject']) : '';
$message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';

// Validate data
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

// Insert message into database
$query = "INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);

if (mysqli_stmt_execute($stmt)) {
    // Success
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Thank you for your message! I will get back to you soon.']);
} else {
    // Error
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again later.']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>