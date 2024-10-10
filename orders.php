<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'php/db.php'; // Make sure this path is correct

// Fetch completed orders
$completed_orders = [];
$sql_completed = "SELECT id, item, price, status FROM orders WHERE user_id = {$_SESSION['user_id']} AND status = 'completed'";
$result_completed = $conn->query($sql_completed);
if ($result_completed->num_rows > 0) {
    while ($row = $result_completed->fetch_assoc()) {
        $completed_orders[] = $row;
    }
}

// Fetch canceled orders
$canceled_orders = [];
$sql_canceled = "SELECT id, item, price, status FROM orders WHERE user_id = {$_SESSION['user_id']} AND status = 'canceled'";
$result_canceled = $conn->query($sql_canceled);
if ($result_canceled->num_rows > 0) {
    while ($row = $result_canceled->fetch_assoc()) {
        $canceled_orders[] = $row;
    }
}

// Fetch ongoing and delivered orders count
$sql_ongoing = "SELECT COUNT(*) as ongoing_count FROM orders WHERE user_id = {$_SESSION['user_id']} AND status IN ('ongoing', 'delivered')";
$result_ongoing = $conn->query($sql_ongoing);
$ongoing_count = ($result_ongoing->num_rows > 0) ? $result_ongoing->fetch_assoc()['ongoing_count'] : 0;

// Fetch canceled/returned orders count
$sql_canceled_count = "SELECT COUNT(*) as canceled_count FROM orders WHERE user_id = {$_SESSION['user_id']} AND status IN ('canceled', 'returned')";
$result_canceled_count = $conn->query($sql_canceled_count);
$canceled_count = ($result_canceled_count->num_rows > 0) ? $result_canceled_count->fetch_assoc()['canceled_count'] : 0;

// Fetch cart items to get total count
$total_cart_items = 0; // Initialize the cart items count
if (isset($_SESSION['cart'])) {
    $total_cart_items = count($_SESSION['cart']); // Assuming cart is stored in session
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Urban Jerseys Store</title>
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
                <?php if (isset($_SESSION['user_name'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #fff;">
                            <i class="fa fa-user"></i> Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
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

<!-- Orders Page Layout -->
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="account.php" class="list-group-item list-group-item-action">My Account</a>
                <a href="orders.php" class="list-group-item list-group-item-action active">Orders</a>
                <a href="inbox.php" class="list-group-item list-group-item-action">Inbox</a>
                <a href="vouchers.php" class="list-group-item list-group-item-action">Vouchers</a>
                <a href="saved_items.php" class="list-group-item list-group-item-action">Saved Items</a>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-header">
                    My Orders
                </div>
                <div class="card-body">
                    <!-- Ongoing and Delivered Orders Section -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Ongoing/Delivered Orders</h5>
                                    <p><strong>Count:</strong> <?php echo $ongoing_count; ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Canceled/Returned Orders Section -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Canceled/Returned Orders</h5>
                                    <p><strong>Count:</strong> <?php echo $canceled_count; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Orders Section -->
                    <h5 class="mt-4">Completed Orders</h5>
                    <?php if (!empty($completed_orders)): ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($completed_orders as $order): ?>
                                    <tr>
                                        <td><?php echo $order['id']; ?></td>
                                        <td><?php echo htmlspecialchars($order['item']); ?></td>
                                        <td><?php echo number_format($order['price'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>You have no completed orders.</p>
                    <?php endif; ?>
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
