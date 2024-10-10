<?php
session_start();

// Include database connection
include 'php/db.php'; // Make sure this path is correct

// Check if item_id is set and valid
if (isset($_POST['item_id'])) {
    $item_id = intval($_POST['item_id']);
    $user_id = $_SESSION['user_id'];

    // Remove the item from saved_items
    $sql = "DELETE FROM saved_items WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $item_id);

    if ($stmt->execute()) {
        echo "Item removed from saved items.";
    } else {
        echo "Error removing item.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
