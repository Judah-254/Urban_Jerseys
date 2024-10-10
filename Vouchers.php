<?php
session_start();

// Include database connection
include 'php/db.php'; // Make sure this path is correct

// Fetch active vouchers
$vouchers = [];
$sql_vouchers = "SELECT code, discount, expiration_date FROM vouchers WHERE status = 'active' AND expiration_date >= CURDATE()";
$result_vouchers = $conn->query($sql_vouchers);
if ($result_vouchers->num_rows > 0) {
    while ($row = $result_vouchers->fetch_assoc()) {
        $vouchers[] = $row;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Vouchers - Urban Jerseys Store</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css"> <!-- Your project-specific stylesheet -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
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
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <?php if (isset($_SESSION['user_name'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #fff;">
                            Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="account.php"><i class="fa fa-user"></i> My Account</a>
                            <a class="dropdown-item" href="orders.php"><i class="fa fa-box"></i> Orders</a>
                            <a class="dropdown-item" href="inbox.php"><i class="fa fa-envelope"></i> Inbox</a>
                            <a class="dropdown-item" href="vouchers.php"><i class="fa fa-ticket-alt"></i> Vouchers</a>
                            <a class="dropdown-item" href="saved_items.php"><i class="fa fa-heart"></i> Saved Items</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>

<!-- Vouchers Page Layout -->
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar Navigation (Same as the first code) -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="account.php" class="list-group-item list-group-item-action">My Account</a>
                <a href="orders.php" class="list-group-item list-group-item-action">Orders</a>
                <a href="inbox.php" class="list-group-item list-group-item-action">Inbox</a>
                <a href="vouchers.php" class="list-group-item list-group-item-action active">Vouchers</a> <!-- Set active for Vouchers -->
                <a href="saved_items.php" class="list-group-item list-group-item-action">Saved Items</a>
            </div>
        </div>

        <div class="col-md-9">
            <h2 class="text-center">Available Vouchers</h2>
            <ul class="list-group mb-4">
                <?php foreach ($vouchers as $voucher): ?>
                    <li class="list-group-item">
                        <strong>Code:</strong> <?php echo htmlspecialchars($voucher['code']); ?><br>
                        <strong>Discount:</strong> $<?php echo htmlspecialchars($voucher['discount']); ?><br>
                        <strong>Expires on:</strong> <?php echo htmlspecialchars($voucher['expiration_date']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="mt-4">
                <h4>Apply Voucher</h4>
                <input type="text" id="voucher_code" class="form-control" placeholder="Enter your voucher code">
                <button id="apply_voucher" class="btn btn-primary mt-2">Apply</button>
                <div id="voucher_response" class="mt-2"></div>
            </div>
            
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#apply_voucher').click(function() {
        var code = $('#voucher_code').val();
        $.post('apply_voucher.php', {code: code}, function(response) {
            $('#voucher_response').html(response);
            $('#voucher_code').val(''); // Clear the input field
        });
    });
});
</script>

</body>
</html>
