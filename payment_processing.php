<?php
session_start();

// Ensure the user is logged in and there's a cart
if (!isset($_SESSION['user_id']) || !isset($_SESSION['cart'])) {
    header("Location: login.php");
    exit();
}

// Calculate total amount including delivery fee
$total_amount = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}
$total_amount += 120; // Add delivery fee

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Processing</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .container:hover {
            transform: translateY(-5px);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .order-summary {
            padding: 20px;
            font-size: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #eee;
        }

        .order-summary .total-amount {
            font-weight: bold;
            color: #007bff;
        }

        .payment-method {
            margin-bottom: 30px;
        }

        .payment-method h3 {
            margin-bottom: 15px;
            color: #333;
        }

        .payment-option {
            margin: 15px 0;
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
        }

        .payment-option:hover {
            background-color: #f0f0f0;
            border-radius: 5px;
        }

        .payment-option input {
            margin-right: 10px;
        }

        .payment-option img {
            height: 30px;
            margin-left: 10px;
        }

        .mobile-money-options, .card-options {
            display: none;
            margin-left: 30px;
            animation: fadeIn 0.5s;
        }

        .pay-now-btn {
            display: block;
            background-color: #007bff;
            color: white;
            padding: 12px;
            text-align: center;
            text-decoration: none;
            font-size: 18px;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
            width: 100%;
        }

        .pay-now-btn:hover {
            background-color: #0056b3;
        }

        footer {
            text-align: center;
            margin-top: 20px;
        }

        footer a {
            color: #007bff;
            text-decoration: none;
        }

        .hidden {
            display: none;
        }

        .phone-input, .card-input {
            margin-top: 10px;
        }

        .phone-input input, .card-input input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        .phone-input input:focus, .card-input input:focus {
            border-color: #007bff;
        }

        .note {
            color: #555;
            font-size: 14px;
            margin-top: 15px;
            text-align: center;
        }

        .help-link {
            color: #007bff;
            text-decoration: none;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            .order-summary {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Payment Processing</h2>
    
    <!-- Order Summary -->
    <div class="order-summary">
        <span>Total to Pay</span>
        <span class="total-amount">Ksh <?php echo number_format($total_amount, 2); ?></span>
    </div>

    <!-- Payment Method Selection -->
    <div class="payment-method">
        <h3>Choose a New Payment Method</h3>
        <form id="payment-form">
            <div class="payment-option">
                <input type="radio" id="mobile-money" name="payment-method" value="mobile" required>
                <label for="mobile-money">Mobile Money</label>
                <img src="images/mpesa.jpg" alt="M-Pesa">
                <img src="images/airtel.jpg" alt="Airtel Money">
            </div>

            <!-- Mobile Money Options with Dropdown -->
            <div class="mobile-money-options hidden" id="mobile-money-options">
                <label for="mobile-provider">Choose Mobile Money Provider</label>
                <select id="mobile-provider" name="mobile-provider">
                    <option value="" disabled selected>Select Provider</option>
                    <option value="mpesa">M-Pesa</option>
                    <option value="airtel">Airtel Money</option>
                </select>

                <!-- Phone Number Input -->
                <div class="phone-input hidden" id="phone-input">
                    <label for="phone-number">Enter your phone number</label>
                    <input type="text" id="phone-number" name="phone-number" placeholder="+254 700 000 000">
                </div>
            </div>

            <div class="payment-option">
                <input type="radio" id="bank-card" name="payment-method" value="card">
                <label for="bank-card">Bank Cards</label>
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" alt="Visa">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a4/Mastercard-logo.png" alt="MasterCard">
            </div>

            <!-- Bank Card Input -->
            <div class="card-options hidden" id="card-options">
                <div class="card-input">
                    <label for="card-number">Card Number</label>
                    <input type="text" id="card-number" name="card-number" placeholder="Enter your card number">
                </div>
                <div class="card-input">
                    <label for="expiry-date">Expiry Date</label>
                    <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY">
                </div>
                <div class="card-input">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" placeholder="CVV">
                </div>
            </div>
            <!-- Pay Now Button -->
<button type="button" class="pay-now-btn" onclick="window.location.href='complete_order.php'">Confirm Payment: Ksh <?php echo number_format($total_amount, 2); ?></button>

        </form>
    </div>

    <!-- Footer -->
    <footer>
        <a href="index.php">Back to Urban Jerseys</a>
    </footer>
</div>

<script>
    // Function to hide or show elements
    function toggleVisibility(elementId, shouldShow) {
        const element = document.getElementById(elementId);
        if (shouldShow) {
            element.classList.remove('hidden');
            element.style.display = 'block';
        } else {
            element.classList.add('hidden');
            element.style.display = 'none';
        }
    }

    // Show mobile money options when Mobile Money is selected
    document.getElementById('mobile-money').addEventListener('click', function() {
        toggleVisibility('mobile-money-options', true); // Show mobile options
        toggleVisibility('card-options', false); // Hide card options
        toggleVisibility('phone-input', false); // Initially hide phone input until provider is selected
    });

    // Show card options when Bank Cards is selected
    document.getElementById('bank-card').addEventListener('click', function() {
        toggleVisibility('card-options', true); // Show card options
        toggleVisibility('mobile-money-options', false); // Hide mobile options
        toggleVisibility('phone-input', false); // Hide phone input for cards
    });

    // Show phone input when a mobile money provider is selected from the dropdown
    document.getElementById('mobile-provider').addEventListener('change', function() {
        const selectedProvider = this.value;
        if (selectedProvider) {
            toggleVisibility('phone-input', true); // Show phone input when provider is selected
        } else {
            toggleVisibility('phone-input', false); // Hide phone input if no provider is selected
        }
    });

    // Handle form submission
    document.getElementById('payment-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const paymentMethod = document.querySelector('input[name="payment-method"]:checked').value;

        if (paymentMethod === 'mobile') {
            const mobileProvider = document.getElementById('mobile-provider').value;
            const phoneNumber = document.getElementById('phone-number').value;

            if (!mobileProvider || !phoneNumber) {
                alert('Please select a mobile money provider and enter your phone number.');
                return;
            }

            alert('Payment successful via ' + mobileProvider + ' for number ' + phoneNumber);
        } else if (paymentMethod === 'card') {
            const cardNumber = document.getElementById('card-number').value;
            const expiryDate = document.getElementById('expiry-date').value;
            const cvv = document.getElementById('cvv').value;

            if (!cardNumber || !expiryDate || !cvv) {
                alert('Please fill in your card details.');
                return;
            }

            alert('Payment successful with card number ' + cardNumber);
        }
    });
</script>

</body>
</html>
