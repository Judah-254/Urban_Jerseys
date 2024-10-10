<?php
session_start(); 
include 'php/db.php';

// Count total items in cart and total cart price
$total_cart_items = 0;
$total_cart_price = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_cart_items += $item['quantity'];
        $total_cart_price += $item['price'] * $item['quantity'];
    }
}

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_name']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
           
                <!-- Show user name and logout if logged in -->
                <?php if ($is_logged_in): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #fff;">
                            <i class="fa fa-user"></i> Hi, <?php echo $_SESSION['user_name']; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="account.php"><i class="fa fa-user"></i> My Account</a>
                            <a class="dropdown-item" href="orders.php"><i class="fa fa-box"></i> Orders</a>
                            <a class="dropdown-item" href="inbox.php"><i class="fa fa-envelope"></i> Inbox</a>
                            <a class="dropdown-item" href="saved_items.php"><i class="fa fa-heart"></i> Saved Items</a>
                            <a class="dropdown-item" href="vouchers.php"><i class="fa fa-ticket-alt"></i> Vouchers</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="index.php" class="nav-link" style="color: #fff;">
                            <i class="fa fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php" style="color: #fff;">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signup.php" style="color: #fff;">Sign Up</a>
                    </li>
                <?php endif; ?>

<!-- Cart Modal -->
 <!-- Cart Icon with Modal Trigger -->
<li class="nav-item">
    <a class="nav-link" href="#" data-toggle="modal" data-target="#cartModal" style="color: #fff;">
        <i class="fa fa-shopping-cart"></i> Cart 
        <span id="cart-count" class="badge badge-pill badge-danger"><?php echo $total_cart_items; ?></span>
    </a>
</li>
<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel" aria-hidden="true"> 
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel" style="color: black;">Your Cart</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color: black;">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="color: black;">
                <?php if (!empty($_SESSION['cart'])): ?>
                    <ul class="list-group">
                        <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center" style="color: black;">
                                <div>
                                    <strong style="color: black;"><?php echo $item['name']; ?></strong><br>
                                    <small>KSH <?php echo number_format($item['price'], 2); ?></small><br>
                                    <span>Quantity: <?php echo $item['quantity']; ?></span>
                                </div>
                                <button class="btn btn-danger btn-sm" onclick="removeFromCart(<?php echo $index; ?>)">Remove</button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p style="color: black;">Your cart is empty!</p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Continue Shopping</button>
                <a href="checkout.php" class="btn btn-primary">Checkout</a>
            </div>
        </div>
    </div>
</div>
<script>
    function removeFromCart(index) {
    // Example AJAX request to remove item from the cart
    $.ajax({
        url: 'remove_from_cart.php', // Your backend script to handle removal
        method: 'POST',
        data: { index: index },
        success: function(response) {
            // Refresh the cart modal or page to show the updated cart
            location.reload();
        }
    });
}
</script>
            </ul>
        </div>
    </nav>

    <!-- Search Bar -->
    <div class="container mt-4 text-center">
        <form action="search.php" method="GET" class="form-inline justify-content-center">
            <input class="form-control mr-sm-2" type="search" name="query" placeholder="Search for a jersey" aria-label="Search" onkeyup="showSuggestions(this.value)" style="width: 50%;">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
        <div id="suggestions" style="display:none; border:1px solid #ccc; padding:10px;"></div>
    </div>
</header>

<!-- Optional content like modals or footer can go here -->

</body>
</html>
