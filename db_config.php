<?php
// Database Configuration
// Update these credentials with your hosting provider's database details

$db_host = "localhost";      // Usually 'localhost' on free hosting
$db_username = "root";       // Your database username
$db_password = "";           // Your database password
$db_name = "hari_carpenter"; // Your database name

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8");

?>
