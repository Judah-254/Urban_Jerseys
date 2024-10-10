<?php
$servername = "localhost"; // XAMPP default server
$username = "root";        // Default MySQL username for XAMPP
$password = "";            // Default password (leave empty)
$dbname = "urban_jerseys_db";  // Database name (create this in phpMyAdmin)

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
