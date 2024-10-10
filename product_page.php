<?php
// Fetch the product details using the product ID from the URL
include 'php/db.php'; // Include your database connection
$product_id = $_GET['id'];

// Query to fetch product data (like name, image, price, and league)
$stmt = $conn->prepare("SELECT id, name, price, image_url, league FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> | Urban Jerseys Store</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Bootstrap 4.5 -->
    <link rel="stylesheet" href="css/styles.css"> <!-- Custom CSS -->
    <style>
        /* Custom styles for the product page */
        body {
            background-color: #f7f7f7;
        }
        .product-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .product-image img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px; /* Adjust margin to place it below total cost */
        }
        .quantity-control button {
            width: 40px;
            height: 40px;
            background-color: transparent; /* Remove the yellow background */
            color: #333; /* Use a dark color for text */
            font-size: 20px;
            border: none; /* Remove border */
            margin: 0 5px;
            cursor: pointer;
            font-weight: bold; /* Make the +/- symbols stand out */
        }
        .quantity-control input {
            width: 50px;
            text-align: center;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin: 0 5px;
            padding: 5px;
        }
        .total-cost-section, .bottom-buttons {
            margin-top: 20px;
        }
        .bottom-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .custom-select {
            width: 100%;
            margin-bottom: 15px;
        }
        .header-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }
        .header-buttons .home-icon, .header-buttons .close-button {
            cursor: pointer;
            font-size: 20px;
        }
    </style>
</head>
<body>

<!-- Header Section with Home Icon and Close Button -->
<div class="container">
    <div class="header-buttons d-flex justify-content-between align-items-center">
        <a href="index.php" class="home-icon">
            <i class="fas fa-home"></i> <!-- Font Awesome Home Icon -->
        </a>
        <!-- Close button -->
        <a href="index.php" class="close-button" style="text-decoration: none; font-size: 24px;">
            &times; <!-- Close "X" symbol -->
        </a>
    </div>
    <style> 
    .close-button {
        font-size: 24px;
        color: #333; /* Dark text color */
        text-decoration: none;
        padding: 5px;
    }

.close-button:hover {
    color: red; /* Change color on hover */
}
</style>
</div>

<div class="container product-container">
    <div class="row">
        <!-- Product Image Section -->
        <div class="col-md-6 product-image">
            <img src="images/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid">
        </div>

        <!-- Product Details Section -->
        <div class="col-md-6 product-details">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            
            <h4>Price: Ksh <span id="base-price"><?php echo number_format($product['price'], 2); ?></span></h4>

            <!-- Size Dropdown -->
            <label for="size">Size</label>
            <select id="size" class="custom-select">
                <option value="Kids Size">Kids Size</option>
                <option value="Small">Small</option>
                <option value="Medium">Medium</option>
                <option value="Large">Large</option>
                <option value="Extra Large">Extra Large</option>
            </select>

            <!-- Customization Option -->
            <label for="customization">Do you want to personalize with name & number? (Ksh 500)</label>
            <select id="customization" class="custom-select">
                <option value="No">No</option>
                <option value="Yes">Yes</option>
            </select>

            <!-- Customization Fields (Name and Number) -->
            <div id="customization-fields" style="display: none;">
                <label for="name">Enter Name</label>
                <input type="text" id="name" class="form-control">

                <label for="number">Enter Number</label>
                <input type="text" id="number" class="form-control">
            </div>

            <!-- Extra Customization - Badges -->
            <label for="badge">Extra Customization - Badges(Ksh 100</label>
            <select id="badge" class="custom-select">
                <option value="None">None</option>
                <option value="EPL Badge">EPL Badge</option>
                <option value="Champions League Badge">Champions League Badge</option>
                <option value="Serie A Badge">Serie A Badge</option>
                <option value="Bundesliga Badge">Bundesliga Badge</option>
            </select>

            <!-- Total Cost -->
            <div class="total-cost-section">
                <h4>Total Cost: Ksh <span id="total-cost"><?php echo number_format($product['price'], 2); ?></span></h4>
            </div>

            <!-- Quantity Selector and Add to Cart & Checkout Buttons -->
            <div class="bottom-buttons">
                <!-- Quantity Control -->
                <div class="quantity-control">
                    <button id="decreaseQuantity">-</button>
                    <input type="text" id="quantity" value="1" readonly>
                    <button id="increaseQuantity">+</button>
                </div>

                <!-- Add to Cart Form -->
                <form id="addToCartForm" action="add_to_cart.php" method="GET">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
                    <input type="hidden" name="quantity" id="quantityField" value="1">
                    <input type="hidden" name="customization" id="customizationField" value="No"> <!-- For customization -->
                    <input type="hidden" name="badge" id="badgeField" value="None"> <!-- For badge -->
                    <button type="button" class="btn btn-primary" id="addToCartButton">Add to Cart</button>
                    <a href="checkout.php" class="btn btn-success">Checkout</a> <!-- Checkout button -->
                </form>
            </div>

            <div id="cartMessage" style="margin-top: 20px;"></div>
            <script>
                // Updating hidden inputs when customization and badge selections are made
                document.getElementById('customization').addEventListener('change', function() {
                    document.getElementById('customizationField').value = this.value;
                });

                document.getElementById('badge').addEventListener('change', function() {
                    document.getElementById('badgeField').value = this.value;
                });

                document.getElementById('addToCartButton').addEventListener('click', function() {
                    var form = document.getElementById('addToCartForm');
                    var formData = new FormData(form);

                    // Use AJAX to send the data to add_to_cart.php without reloading the page
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', 'add_to_cart.php?' + new URLSearchParams(formData), true);
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            var messageContainer = document.getElementById('cartMessage');

                            // Display success or error message at the top of the page
                            if (response.status === 'success') {
                                messageContainer.innerHTML = `<div class="alert alert-success">${response.message}</div>`;
                            } else {
                                messageContainer.innerHTML = `<div class="alert alert-danger">${response.message}</div>`;
                            }
                        }
                    };
                    xhr.send();
                });
            </script>

            <script>
                // Variables
                const basePrice = <?php echo $product['price']; ?>;
                const customizationCost = 500;
                let quantity = 1;
                let totalCost = basePrice;

                // Elements
                const quantityInput = document.getElementById('quantity');
                const customizationSelect = document.getElementById('customization');
                const customizationFields = document.getElementById('customization-fields');
                const totalCostElement = document.getElementById('total-cost');

                // Hidden fields for the form
                const quantityField = document.getElementById('quantityField');
                const customizationField = document.getElementById('customizationField');
                const nameField = document.getElementById('nameField');
                const numberField = document.getElementById('numberField');
                const badgeField = document.getElementById('badgeField');
                const totalCostField = document.getElementById('totalCostField');

                // Handle Customization Selection
                customizationSelect.addEventListener('change', function() {
                    if (customizationSelect.value === 'Yes') {
                        customizationFields.style.display = 'block';
                        totalCost += customizationCost;
                    } else {
                        customizationFields.style.display = 'none';
                        totalCost -= customizationCost;
                    }
                    updateTotalCost();
                });

                // Handle Quantity Increase/Decrease
                document.getElementById('increaseQuantity').addEventListener('click', function() {
                    quantity += 1;
                    quantityInput.value = quantity;
                    updateTotalCost();
                });

                document.getElementById('decreaseQuantity').addEventListener('click', function() {
                    if (quantity > 1) {
                        quantity -= 1;
                        quantityInput.value = quantity;
                        updateTotalCost();
                    }
                });

                // Update Total Cost and Hidden Fields Function
                function updateTotalCost() {
                    const currentCost = basePrice * quantity + (customizationSelect.value === 'Yes' ? customizationCost : 0);
                    totalCostElement.textContent = currentCost.toLocaleString();

                    // Update hidden fields to send data to add_to_cart.php
                    totalCostField.value = currentCost;
                    quantityField.value = quantity;
                    customizationField.value = customizationSelect.value;
                    nameField.value = document.getElementById('name').value;
                    numberField.value = document.getElementById('number').value;
                    badgeField.value = document.getElementById('badge').value;
                }

                // Initial call to set the correct quantity in the form on page load
                updateTotalCost();
            </script>
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> <!-- Font Awesome -->
</body>
</html>
