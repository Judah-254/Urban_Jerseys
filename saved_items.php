<?php
session_start();

// Include database connection
include 'php/db.php'; // Make sure this path is correct

// Fetch saved items for the logged-in user
$saved_items = [];
$sql_saved_items = "SELECT p.id, p.name, p.price, p.image_url 
                    FROM saved_items s
                    JOIN products p ON s.product_id = p.id 
                    WHERE s.user_id = {$_SESSION['user_id']}";
$result_saved_items = $conn->query($sql_saved_items);
if ($result_saved_items->num_rows > 0) {
    while ($row = $result_saved_items->fetch_assoc()) {
        $saved_items[] = $row;
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
    <title>Saved Items - Urban Jerseys Store</title>
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

<!-- Saved Items Page Layout -->
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar Navigation (Same design as in vouchers.php) -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="account.php" class="list-group-item list-group-item-action">My Account</a>
                <a href="orders.php" class="list-group-item list-group-item-action">Orders</a>
                <a href="inbox.php" class="list-group-item list-group-item-action">Inbox</a>
                <a href="vouchers.php" class="list-group-item list-group-item-action">Vouchers</a>
                <a href="saved_items.php" class="list-group-item list-group-item-action active">Saved Items</a>
            </div>
        </div>

        <div class="col-md-9">
            <h2 class="text-center">Saved Items</h2>
            <?php if (empty($saved_items)): ?>
                <div class="alert alert-info" role="alert">
                    You have no saved items. Start browsing and save your favorite products!
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($saved_items as $item): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" class="card-img-top" alt="Product Image">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                                    <p class="card-text">$<?php echo htmlspecialchars($item['price']); ?></p>
                                    <button class="btn btn-danger remove-item" data-item-id="<?php echo $item['id']; ?>">Remove</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.remove-item').click(function() {
        var itemId = $(this).data('item-id');
        $.post('remove_saved_item.php', {item_id: itemId}, function(response) {
            alert(response); // Show a confirmation message
            location.reload(); // Reload the page to reflect the change
        });
    });
});
</script>

</body>
</html>
