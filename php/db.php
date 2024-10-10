<?php

if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
    // Local server (e.g., XAMPP)
    $servername = "localhost"; // XAMPP default server
    $username = "root";        // Default MySQL username for XAMPP
    $password = "";            // Default password for XAMPP (usually empty)
    $dbname = "urban_jerseys_db"; // Your local database name
} else {
    // Remote server
    $servername = "localhost";    // Remote server's hostname (usually localhost too)
    $username = "majhubcl_urbanjersey";        // Remote server MySQL username
    $password = "majhubcl_urbanjersey";        // Remote server MySQL password
    $dbname = "majhubcl_urbanjersey";          // Remote server database name
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
