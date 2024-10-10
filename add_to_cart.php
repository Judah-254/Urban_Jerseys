<?php
session_start();
include 'php/db.php';

$product_id = $_GET['id'];
$submitted_quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1; // Get the quantity from the form submission
$customization = isset($_GET['customization']) ? $_GET['customization'] : 'No';
$badge = isset($_GET['badge']) ? $_GET['badge'] : 'None';

// Fetch product from database
$result = mysqli_query($conn, "SELECT * FROM products WHERE id = '$product_id'");
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo json_encode(['status' => 'error', 'message' => 'Product not found']);
    exit();
}

// Calculate additional customization cost
$customization_cost = 0;
if ($customization == 'Yes') {
    $customization_cost += 500; // Add 500 for customization
}

// Add badge cost if any (assuming each badge has a different price, set your own values)
$badge_cost = 0;
if ($badge != 'None') {
    $badge_cost = 100; // Example: each badge costs 100
}

// Calculate the final price with customization and badge
$total_price = ($product['price'] + $customization_cost + $badge_cost) * $submitted_quantity;

// Add product to cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if the product is already in the cart
$product_in_cart = false;
foreach ($_SESSION['cart'] as &$cart_item) {
    if ($cart_item['id'] == $product['id'] && $cart_item['customization'] == $customization && $cart_item['badge'] == $badge) {
        // Add the submitted quantity to the existing quantity
        $cart_item['quantity'] += $submitted_quantity;
        $product_in_cart = true;
        break;
    }
}

// If not in cart, add it as a new item with the customization and badge
if (!$product_in_cart) {
    $_SESSION['cart'][] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $total_price, // Use the calculated total price
        'quantity' => $submitted_quantity,
        'customization' => $customization,
        'badge' => $badge
    ];
}

// Return success message
echo json_encode(['status' => 'success', 'message' => 'Product added to cart with customization']);
