<?php
session_start();
include 'php/db.php'; // Database connection

$name = mysqli_real_escape_string($conn, $_POST['name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

// Insert new user into the database
$query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

if (mysqli_query($conn, $query)) {
    $user_id = mysqli_insert_id($conn); // Get newly created user ID
    
    // Store user info in session
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $name;

    // Redirect to home page after successful signup
    header("Location: index.php");
    exit();
} else {
    $_SESSION['error'] = "Error creating account: " . mysqli_error($conn);
    header("Location: signup.php");
    exit();
}
?>
