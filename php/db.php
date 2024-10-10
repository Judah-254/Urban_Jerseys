<?php
$servername = "localhost"; // XAMPP default server
$username = "majhubcl_urbanjersey";        // Default MySQL username for XAMPP
$password = "majhubcl_urbanjersey";            // Default password (leave empty)
$dbname = "majhubcl_urbanjersey";  // Database name (create this in phpMyAdmin)

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
