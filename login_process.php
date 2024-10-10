<?php
session_start();
include 'php/db.php'; // Database connection

$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password']; // Do not hash yet; the hash will be verified

// Check if the user exists in the database
$query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);

    // Verify the password (assuming passwords are hashed in the database)
    if (password_verify($password, $user['password'])) {
        // Store user info in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name']; // Set user name in session
        
        // Redirect to home page after successful login
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid password.";
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION['error'] = "No account found with that email.";
    header("Location: login.php");
    exit();
}
?>
