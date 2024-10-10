<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'php/db.php'; // Include the database connection

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];  // Get the logged-in user's ID
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $street_address = mysqli_real_escape_string($conn, $_POST['street_address']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);  // Corrected to match 'name="location"'
    
    // Insert the address into the database
    $sql = "INSERT INTO addresses (user_id, full_name, phone_number, street_address, location) 
            VALUES ('$user_id', '$full_name', '$phone_number', '$street_address', '$location')";

    if (mysqli_query($conn, $sql)) {
        // Redirect to accounts page after successful address addition
        header("Location: account.php?success=1"); // Add success flag in query string
        exit();
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Address</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            margin-top: 20px;
        }
        input[type="text"], input[type="tel"], select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            margin-bottom: 15px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Address</h2>

    <!-- Display success or error messages -->
    <?php if (isset($error_message)) { ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php } ?>

    <!-- Address Form -->
    <form action="address_book.php" method="POST">
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required>

        <label for="phone_number">Phone Number</label>
        <input type="tel" id="phone_number" name="phone_number" placeholder="Enter your phone number" required>

        <label for="street_address">Street Address</label>
        <input type="text" id="street_address" name="street_address" placeholder="Enter your street address" required>

        <label for="location">Location</label> <!-- Corrected 'name' attribute to match location -->
        <select id="location" name="location" required>
            <option value="" disabled selected>Select your Location</option>
            <option value="Embu Town">Embu Town</option>
            <option value="University Area">University Area</option>
            <option value="Kangaru">Kangaru</option>
            <option value="Njukiri">Njukiri</option>
            <option value="Kayole">Kayole</option><!-- Add more locations as needed -->
        </select>

        <input type="submit" value="Add Address">
    </form>
</div>

</body>
</html>
