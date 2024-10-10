<?php
session_start();

// Check if the cart is set and index is provided
if (isset($_SESSION['cart']) && isset($_POST['index'])) {
    $index = intval($_POST['index']);
    
    // Remove the item from the cart if it exists
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        
        // Reindex the cart array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    
    // Return success response
    echo json_encode(['success' => true]);
} else {
    // Return error response
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
