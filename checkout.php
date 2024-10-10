<?php
session_start();
include 'php/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize account balance for simulation purposes
$account_balance = 000; // Example balance
$total_amount = 0;

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    echo "<script>alert('Your cart is empty! Please add items before checking out.'); window.location.href='index.php';</script>";
    exit();
}

// Calculate total amount from the cart
foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        #checkout-container {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: auto;
        }

        .form-container {
            width: 65%;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .summary-container-wrapper {
            width: 30%;
            margin-left: 20px;
        }

        .summary-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 20px;
        }

        .section {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ccc;
        }

        .section h2 {
            margin-bottom: 10px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }

        #confirm-payment-btn {
            background-color: #007bff;
        }

        #confirm-payment-btn:hover {
            background-color: #0069d9;
        }

        .hidden {
            display: none;
        }

        #order-summary {
            text-align: center;
        }

        .disabled-btn {
            background-color: gray;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

<div id="checkout-container">
    <!-- Form Container -->
    <div class="form-container">
        <!-- Customer Address Section -->
        <div id="address-section" class="section">
            <h2>1. Customer Address</h2>
            <form id="address-form">
                <label for="first-name">First Name</label>
                <input type="text" id="first-name" name="first-name" placeholder="Enter First Name" required>

                <label for="last-name">Last Name</label>
                <input type="text" id="last-name" name="last-name" placeholder="Enter Last Name" required>

                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" placeholder="Enter Phone Number" required>

                <label for="address">Address</label>
                <input type="text" id="address" name="address" placeholder="Enter Address" required>

                <label for="region">Location</label>
                <select id="region" name="region" required>
                    <option value="Embu Town">Embu Town</option>
                    <option value="University Area">University Area</option>
                    <option value="Kangaru">Kangaru</option>
                    <option value="Njukiri">Njukiri</option>
                    <option value="Kayole">Kayole</option>
                </select>
            </form>
            <button id="next-to-delivery">Next: Delivery Details</button>
        </div>

        <!-- Delivery Details Section (Hidden initially) -->
        <div id="delivery-section" class="section hidden">
            <h2>2. Delivery Details</h2>
            <form id="delivery-form">
                <label for="delivery-method">Select Delivery Method</label>
                <select id="delivery-method" name="delivery-method" required>
                    <option value="pickup">Pick-up Station</option>
                    <option value="home-delivery">Home Delivery</option>
                </select>

                <div id="pickup-station-container" class="hidden">
                    <label for="pickup-station">Select Pickup Station</label>
                    <select id="pickup-station" name="pickup-station" required>
                    <option value="Embu Town">Embu Town</option>
                    <option value="University Area">University Area</option>
                    <option value="Kangaru">Kangaru</option>
                    <option value="Njukiri">Njukiri</option>
                    <option value="Kayole">Kayole</option>
                    </select>
                </div>
            </form>
            <button id="next-to-payment">Next: Payment Method</button>
        </div>

        <!-- Payment Method Section (Hidden initially) -->
        <div id="payment-section" class="section hidden">
            <h2>3. Payment Method</h2>
            <form id="payment-form">
                <label for="payment-method">Select Payment Method</label>
                <select id="payment-method" name="payment-method" required>
                    <option value="mpesa">Pay Now (M-Pesa, Airtel, Bank Cards)</option>
                    <option value="pay-on-delivery">Pay on Delivery (with Mobile Money or Bank Cards)</option>
                </select>

                <button type="button" id="confirm-payment-btn">Confirm Payment Method</button>
            </form>
        </div>
    </div>

    <!-- Order Summary Section (Separate container) -->
    <div class="summary-container-wrapper">
        <div class="summary-container" id="order-summary">
            <h3>Order Summary</h3>
            <p>Item(s) Total: Ksh <?php echo number_format($total_amount, 2); ?></p>
            <p>Delivery Fee: <span id="delivery-fee">Ksh 0</span></p>
            <p>Total: <span id="total-fee">Ksh <?php echo number_format($total_amount, 2); ?></span></p>
            <button id="confirm-order-btn">Confirm Order</button>
            <p>(Complete the steps in order to proceed)</p>
        </div>
    </div>
</div>

<script>
    const deliveryCharges = {
        "Embu Town": 120,
        "University Area": 120,
        "Kangaru": 120,
        "Njukiri": 120,
        "Kayole": 120
    };

    // Step-by-step navigation and validation
    document.getElementById('next-to-delivery').addEventListener('click', () => {
        if (document.getElementById('first-name').value &&
            document.getElementById('last-name').value &&
            document.getElementById('phone').value &&
            document.getElementById('address').value &&
            document.getElementById('region').value) {
            document.getElementById('address-section').classList.add('hidden');
            document.getElementById('delivery-section').classList.remove('hidden');
        } else {
            alert('Please fill out all required fields.');
        }
    });

    // Handle delivery method selection and display pickup station when necessary
    document.getElementById('delivery-method').addEventListener('change', function () {
        if (this.value === 'pickup') {
            document.getElementById('pickup-station-container').classList.remove('hidden');
        } else {
            document.getElementById('pickup-station-container').classList.add('hidden');
        }
    });

    document.getElementById('next-to-payment').addEventListener('click', () => {
        const deliveryMethod = document.getElementById('delivery-method').value;
        const selectedRegion = document.getElementById('region').value;
        const deliveryFee = deliveryCharges[selectedRegion] || 0;

        if ((deliveryMethod === 'pickup' && document.getElementById('pickup-station').value) ||
            deliveryMethod === 'home-delivery') {
            document.getElementById('delivery-section').classList.add('hidden');
            document.getElementById('payment-section').classList.remove('hidden');

            // Update delivery fee and total
            document.getElementById('delivery-fee').textContent = `Ksh ${deliveryFee}`;
            const total = <?php echo json_encode($total_amount); ?> + deliveryFee;
            document.getElementById('total-fee').textContent = `Ksh ${total.toFixed(2)}`;
        } else {
            alert('Please select a delivery method and station (if applicable).');
        }
    });

    document.getElementById('confirm-payment-btn').addEventListener('click', () => {
        if (document.getElementById('payment-method').value) {
            document.getElementById('confirm-order-btn').classList.remove('disabled-btn');
            document.getElementById('confirm-order-btn').disabled = false;
        }
    });

    // Confirm Order Button Click Handler
    document.getElementById('confirm-order-btn').addEventListener('click', () => {
        const paymentMethod = document.getElementById('payment-method').value;
        const totalAmount = document.getElementById('total-fee').textContent;

        if (paymentMethod === 'mpesa') {
            // Redirect to payment processing page
            window.location.href = 'payment_processing.php'; // Change this to your actual payment processing URL
        } else if (paymentMethod === 'pay-on-delivery') {
            alert('You will pay ' + totalAmount + ' on delivery.');
        }
    });
src="https://code.jquery.com/jquery-3.6.0.min.js"

    $(document).ready(function() {
        // Enable button when required (if using validation logic, enable it after validations are passed)
        $('#confirm-order-btn').removeAttr('disabled');

        // Handle button click event
        $('#confirm-order-btn').click(function() {
            $.ajax({
                url: 'complete_order.php',  // The PHP file that processes the order
                method: 'POST',
                dataType: 'json',  // Expect JSON response from the server
                success: function(response) {
                    if (response.status === 'success') {
                        // Display success message and new balance
                        $('#order-status').html(`
                            <h2>Order Successful!</h2>
                            <p>${response.message}</p>
                            <p>Your new balance is: $${response.new_balance.toFixed(2)}</p>
                        `);
                    } else {
                        // Display error message
                        $('#order-status').html(`
                            <h2>Order Failed</h2>
                            <p>${response.message}</p>
                        `);
                    }
                },
                error: function() {
                    // Handle unexpected errors
                    $('#order-status').html(`
                        <h2>Order Failed</h2>
                        <p>There was an unexpected error while placing the order.</p>
                    `);
                }
            });
        });
    });
</script>

</body>
</html>
