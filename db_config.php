<?php
// Database Configuration
// IMPORTANT: Update these credentials with your hosting provider's database details
// NEVER commit real credentials to version control!

$db_host = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "hari_carpenter";

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    // Don't expose database errors to users
    error_log("Database connection failed: " . $conn->connect_error);
    die("An error occurred. Please try again later.");
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

// Enable prepared statements
$conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);

// Set timezone
date_default_timezone_set('Asia/Kolkata');

// Security headers - IMPORTANT: Add to your web server config or .htaccess instead of here
// header("X-Frame-Options: SAMEORIGIN");
// header("X-Content-Type-Options: nosniff");
// header("X-XSS-Protection: 1; mode=block");
// header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
// header("Content-Security-Policy: default-src 'self'");
?>
