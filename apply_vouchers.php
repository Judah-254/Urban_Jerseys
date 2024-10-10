<?php
session_start();
include 'php/db.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    echo 'Please log in to apply a voucher.';
    exit;
}

$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
$voucher_code = $_POST['code'];

// Check if voucher exists and is still active
$sql = "SELECT * FROM vouchers WHERE code = ? AND status = 'active' AND expiration_date >= CURDATE()";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $voucher_code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo 'Invalid or expired voucher code.';
} else {
    // Apply the voucher to the user's cart (or order)
    $voucher = $result->fetch_assoc();
    $discount = $voucher['discount'];

    // Optional: Check if the voucher has already been used by this user
    $check_claimed_sql = "SELECT * FROM user_vouchers WHERE user_id = ? AND voucher_id = ?";
    $check_claimed_stmt = $conn->prepare($check_claimed_sql);
    $check_claimed_stmt->bind_param('ii', $user_id, $voucher['id']);
    $check_claimed_stmt->execute();
    $claimed_result = $check_claimed_stmt->get_result();

    if ($claimed_result->num_rows > 0) {
        echo 'You have already claimed this voucher.';
    } else {
        // Apply discount logic here, for example, to the user's current order/cart
        echo 'Voucher applied! Discount: $' . $discount;

        // Save the claimed voucher to the user_vouchers table
        $claim_sql = "INSERT INTO user_vouchers (user_id, voucher_id) VALUES (?, ?)";
        $claim_stmt = $conn->prepare($claim_sql);
        $claim_stmt->bind_param('ii', $user_id, $voucher['id']);
        $claim_stmt->execute();
    }
}
?>
