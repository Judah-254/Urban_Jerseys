<?php
session_start();

// Clear the cart after successful checkout
if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        .success-message {
            border: 1px solid #4CAF50;
            background-color: #dff0d8;
            color: #3c763d;
            padding: 20px;
            display: inline-block;
            border-radius: 5px;
        }
        a {
            text-decoration: none;
            color: #ffffff;
            background-color: #4CAF50;
            padding: 10px 20px;
            border-radius: 5px;
        }
        a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="success-message">
    <h1>Payment Successful!</h1>
    <p>Your order has been processed successfully.</p>
    <p>Thank you for your purchase!</p>
    <a href="index.php">Return to Home</a>
</div>

</body>
</html>
