<?php
require_once '../includes/config.php';

// This script should be run once to set up the admin user
// After running, you should delete this file for security

// Check if admin user already exists
$query = "SELECT COUNT(*) as count FROM admin_users";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row['count'] > 0) {
    echo "Admin user already exists. For security, please delete this file.";
    exit;
}

// Create admin user (username: admin, password: Admin@123)
$username = 'admin';
$password = 'Admin@123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO admin_users (username, password) VALUES (?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);

if (mysqli_stmt_execute($stmt)) {
    echo "Admin user created successfully!<br>";
    echo "Username: admin<br>";
    echo "Password: Admin@123<br><br>";
    echo "Please change this password immediately after logging in.<br>";
    echo "For security, please delete this file now.<br><br>";
    echo "<a href='login.php'>Go to Login Page</a>";
} else {
    echo "Error creating admin user: " . mysqli_error($conn);
}
?>