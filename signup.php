<?php
include 'php/db.php';
session_start();

if (isset($_POST['signup'])) {
    // Retrieve user input safely
    $email = $_POST['email'];
    $hashed_password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $name = $_POST['name']; // Assuming you have a name field

    // Prepare the SQL statement to insert the new user
    $sql = "INSERT INTO users (name, email, password, balance) VALUES (?, ?, ?, 20000)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $hashed_password);
    
    // Execute the prepared statement
    if ($stmt->execute()) {
        // Get the last inserted user ID
        $_SESSION['user_id'] = $stmt->insert_id; // use insert_id from prepared statement
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $name; // Set name in session if needed

        // Redirect to the index page
        header("Location: index.php");
        exit(); // Ensure no further code is executed after redirection
    } else {
        $_SESSION['error'] = "Error: Could not sign up.";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Urban Jerseys Store</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Link to CSS file -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="signup-container container mt-5">
        <h2>Sign Up</h2>
        <form action="signup.php" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email address:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" name="signup" class="btn btn-primary btn-block">Sign Up</button>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger mt-2" role="alert">
                    <?php echo $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        </form>
        <p class="mt-3">Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</body>
</html>
