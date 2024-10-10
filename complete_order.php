<?php
session_start();

// Debugging: Log the session cart contents
if (isset($_SESSION['cart'])) {
    error_log(print_r($_SESSION['cart'], true)); // Log the cart contents for review
} else {
    error_log("Cart is not set in session.");
}

include 'php/db.php';

// Check if the cart is not empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo json_encode(['status' => 'error', 'message' => 'Cart is empty']);
    exit();
}

// Capture user ID and fetch current balance
$user_id = $_SESSION['user_id']; // Assuming user ID is stored in the session
$user_query = "SELECT balance FROM users WHERE id = '$user_id'";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

if (!$user) {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    exit();
}

$current_balance = $user['balance']; // Get user's current balance

// Calculate the total order price
$total_order_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $unit_price = isset($item['unit_price']) ? $item['unit_price'] : 0;
    $quantity = isset($item['quantity']) ? $item['quantity'] : 1; // Default to 1 if not set
    $total_price = isset($item['total_price']) ? $item['total_price'] : $unit_price * $quantity;

    $total_order_price += $total_price;
}

// Check if the user has enough balance
if ($total_order_price > $current_balance) {
    echo json_encode(['status' => 'error', 'message' => 'Insufficient balance']);
    exit();
}

// Start a transaction to ensure atomicity
mysqli_begin_transaction($conn);

// Insert the order into the orders table
$order_query = "INSERT INTO orders (user_id, total_price, order_date) VALUES ('$user_id', '$total_order_price', NOW())";
if (mysqli_query($conn, $order_query)) {
    $order_id = mysqli_insert_id($conn);

    // Insert each item in the order into the order_items table
    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['id'];
        $quantity = isset($item['quantity']) ? $item['quantity'] : 1; // Default to 1
        $price = isset($item['unit_price']) ? $item['unit_price'] : 0; // Ensure this key exists
        $customization = isset($item['customization']) ? $item['customization'] : null;
        $badge = isset($item['badge']) ? $item['badge'] : null;

        $item_query = "INSERT INTO order_items (order_id, product_id, quantity, price, customization, badge)
                       VALUES ('$order_id', '$product_id', '$quantity', '$price', '$customization', '$badge')";
        if (!mysqli_query($conn, $item_query)) {
            mysqli_rollback($conn); // Rollback on failure
            error_log("SQL Error: " . mysqli_error($conn));
            echo json_encode(['status' => 'error', 'message' => 'Error adding order item: ' . mysqli_error($conn)]);
            exit();
        }
    }

    // Deduct the total order price from the user's balance
    $new_balance = $current_balance - $total_order_price;
    $update_balance_query = "UPDATE users SET balance = '$new_balance' WHERE id = '$user_id'";
    if (!mysqli_query($conn, $update_balance_query)) {
        mysqli_rollback($conn); // Rollback if balance update fails
        echo json_encode(['status' => 'error', 'message' => 'Failed to update balance: ' . mysqli_error($conn)]);
        exit();
    }

    // Commit the transaction if everything went well
    mysqli_commit($conn);

    // Set the new balance in the session for the success page
    $_SESSION['new_balance'] = $new_balance;

    // Clear the cart after successful order placement
    unset($_SESSION['cart']);

    // Redirect to the success page
    header("Location: payment_success.php");
    exit();
} else {
    // Handle the error and rollback the transaction
    mysqli_rollback($conn);
    echo json_encode(['status' => 'error', 'message' => 'Error placing order: ' . mysqli_error($conn)]);
}
?>
