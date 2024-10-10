<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the new balance from the session or a query parameter (you can also pass this value from the previous page)
$new_balance = isset($_SESSION['new_balance']) ? $_SESSION['new_balance'] : 0;

// Clear the cart session variable
unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 600px;
        }

        h2 {
            color: #28a745; /* Green color for success */
        }

        p {
            font-size: 18px;
            margin: 20px 0;
        }

        .back-home {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .back-home:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Payment Successful!</h2>
    <p>Your payment has been processed successfully.</p>
    <p>New Account Balance: Ksh <?php echo number_format($new_balance, 2); ?></p>
    <a href="index.php" class="back-home">Return Home</a>
</div>

</body>
</html>
