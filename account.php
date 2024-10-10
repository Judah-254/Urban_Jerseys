<?php
session_start();

// Fetch user data
$user_name = $_SESSION['user_name'] ?? 'Guest';
$user_email = $_SESSION['user_email'] ?? 'guest@example.com';
$default_shipping_address = $_SESSION['shipping_address'] ?? 'No default shipping address available.';
$credit_balance = 0; // Example credit balance from DB or session

// Ensure that the cart is defined in the session
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_cart_items = count($cart_items);
$is_logged_in = isset($_SESSION['user_name']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Overview - Urban Jerseys Store</title>

    <!-- Bootstrap and Custom CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css"> <!-- Your project-specific stylesheet -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- FontAwesome icons -->
</head>
<body>

<!-- Header Section -->
<header>
    <h1 class="text-center my-3">Urban Jerseys Store</h1>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                    <a class="nav-link" href="index.php" style="color: #fff;"><i class="fa fa-home"></i> Home</a>
                </li>
                <!-- Show user name and logout if logged in -->
                <?php if ($is_logged_in): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #fff;">
                            <i class="fa fa-user"></i> Hi, <?php echo htmlspecialchars($user_name); ?>
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
                    <!-- Home Icon and Login -->
                    <li class="nav-item">
                        <a href="index.php" class="nav-link" style="color: #fff;"><i class="fa fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php" style="color: #fff;">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signup.php" style="color: #fff;">Sign Up</a>
                    </li>
                <?php endif; ?>                
            </ul>
        </div>
    </nav>
</header>

<!-- Search Bar Section -->
<div class="container mt-4 text-center">
    <form action="search.php" method="GET" class="form-inline justify-content-center">
        <input class="form-control mr-sm-2" type="search" name="query" placeholder="Search for a jersey" aria-label="Search" onkeyup="showSuggestions(this.value)" style="width: 50%;">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
    <div id="suggestions" style="display:none; border:1px solid #ccc; padding:10px;"></div>
</div>

<!-- Account Page Layout -->
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="account.php" class="list-group-item list-group-item-action active">My Account</a>
                <a href="orders.php" class="list-group-item list-group-item-action">Orders</a>
                <a href="inbox.php" class="list-group-item list-group-item-action">Inbox</a>
                <a href="vouchers.php" class="list-group-item list-group-item-action">Vouchers</a>
                <a href="saved_items.php" class="list-group-item list-group-item-action">Saved Items</a>
            </div>
        </div>

        <!-- Account Overview Section -->
        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-header">
                    Account Overview
                </div>
                <div class="card-body">
                    <!-- Account Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Account Details</h5>
                                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user_name); ?></p>
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user_email); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Address Book -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Address Book</h5>
                                    <p>Your default shipping address:</p>
                                    <p><?php echo htmlspecialchars($default_shipping_address); ?></p>
                                    <a href="address_book.php" class="btn btn-link">Add Default Address</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Store Credit Section -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Jersey Store Credit</h5>
                                    <p>Store Credit Balance: KSH <?php echo number_format($credit_balance, 2); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Sections (e.g., Recently Viewed, Followed Sellers) -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include necessary scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
