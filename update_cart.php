<?php
session_start();
include 'php/db.php'; // Ensure the database connection is included

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    // Fetch the product details
    $query = "SELECT * FROM products WHERE id = $productId";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);

        // Initialize cart if not already set
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if product already exists in the cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product['id']) {
                $item['quantity']++;
                $found = true;
                break;
            }
        }

        // If the product is not already in the cart, add it
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
            ];
        }

        // Send success response
        echo json_encode(['status' => 'success', 'message' => 'Product added to cart']);
    } else {
        // Send error response if the product is not found
        echo json_encode(['status' => 'error', 'message' => 'Product not found']);
    }
} else {
    // Send error response if no product ID is passed
    echo json_encode(['status' => 'error', 'message' => 'No product ID provided']);
}
?>
