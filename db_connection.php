<?php
// Database credentials
$servername = "localhost";  // Server name (usually localhost for XAMPP)
$username = "root";         // Default username for XAMPP MySQL
$password = "";             // Default password is empty in XAMPP
$dbname = "details"; // Replace with the name of your database

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
error_log("Connected successfully");
?>