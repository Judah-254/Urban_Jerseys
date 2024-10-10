<?php
session_start();
include 'php/db.php'; // Ensure the database connection is included

// Fetch total sales from the orders table for the current day
$result = mysqli_query($conn, "SELECT COUNT(*) AS total_sales FROM orders WHERE DATE(order_date) = CURDATE()");
$row = mysqli_fetch_assoc($result);
$total_sales = $row['total_sales'];

// Count total items in cart and total cart price
$total_cart_items = 0;
$total_cart_price = 0;

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        // Check if 'price' key exists before using it
        if (isset($item['price'])) {
            $total_cart_items += $item['quantity'];
            $total_cart_price += $item['price'] * $item['quantity'];
        } else {
            // Handle case when 'price' is missing, optional
            echo "Error: Product price not found.";
        }
    }
}


// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_name']);

// Fetch new products (is_new = 1) for display
$new_products_query = "SELECT * FROM products WHERE is_new = 1 ORDER BY created_at DESC LIMIT 6";

// Debug: Echo the query to check its correctness


// Execute the query and check for errors
$new_products_result = mysqli_query($conn, $new_products_query);

// Error handling
if (!$new_products_result) {
    // Output the error
    echo "Error executing query: " . mysqli_error($conn);
    // Optionally, you can stop further execution
    exit;
}

// Fetch jerseys based on their name
$main_products_query = "SELECT * FROM products WHERE name LIKE '%jersey%'"; // Update query to match 'jersey' in name
$main_products_result = mysqli_query($conn, $main_products_query);

if (!$main_products_result) {
    echo "Error executing query: " . mysqli_error($conn);
    exit;
}

// Discount of the Day Information
$discount_message = "Discount of the Day: Get 10% off on selected jerseys!";

// Handle "Buy 2 Jerseys, Get 20% Off" promotion
$promotion_applied = false;
if (isset($_SESSION['cart']) && count($_SESSION['cart']) >= 2) {
    $promotion_applied = true;
    $discount_amount = 0.20 * $total_cart_price; // 20% off
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Jerseys Store</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Link to CSS file -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.8.2/js/all.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

</head>
<body>

<header>
    <h1 class="text-center my-3">Urban Jerseys Store</h1>
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
        <a class="dropdown-item" href="account.php">
            <i class="fa fa-user"></i> My Account
        </a>
        <a class="dropdown-item" href="orders.php">
            <i class="fa fa-box"></i> Orders
        </a>
        <a class="dropdown-item" href="inbox.php">
            <i class="fa fa-envelope"></i> Inbox
        </a>
        <a class="dropdown-item" href="saved_items.php">
            <i class="fa fa-heart"></i> Saved Items
        </a>
        <a class="dropdown-item" href="vouchers.php">
            <i class="fa fa-ticket-alt"></i> Vouchers
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item text-danger" href="logout.php">
            <i class="fa fa-sign-out-alt"></i> Logout
        </a>
    </div>
</li>
                <?php else: ?>
                        <!-- Home Icon and Login -->
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

                <!-- Cart Icon with Cart Count -->
                <li class="nav-item dropdown">
    <a class="nav-link" href="#" data-toggle="modal" data-target="#cartModal" style="color: #fff;">
        <i class="fa fa-shopping-cart"></i> Cart 
        <span id="cart-count" class="badge badge-pill badge-danger"><?php echo $total_cart_items; ?></span>
    </a>
</li>


                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarCartDropdown">
                        <?php if (!empty($_SESSION['cart'])): ?>
                            <?php foreach ($_SESSION['cart'] as $item): ?>
                                <a class="dropdown-item" href="#">
                                    <?php echo $item['name']; ?> (<?php echo $item['quantity']; ?>) - KSH <?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                </a>
                            <?php endforeach; ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center" href="view_cart.php">View Cart</a>
                        <?php else: ?>
                            <p class="dropdown-item">Your cart is empty!</p>
                        <?php endif; ?>
                    </div>
                </li>
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

<!-- Category Links -->
<div class="container mt-3 text-center">
    <a href="epl.php" class="btn btn-primary mx-1">EPL</a>
    <a href="laliga.php" class="btn btn-primary mx-1">La Liga</a>
    <a href="seriea.php" class="btn btn-primary mx-1">Serie A</a>
    <a href="bundesliga.php" class="btn btn-primary mx-1">Bundesliga</a>
</div>
<!-- Offers and Discounts Carousel Section -->
<div id="offersCarousel" class="carousel slide mt-5" data-ride="carousel">
    <div class="carousel-inner">
        <!-- Offer 1: Buy 2 Jerseys, Get 20% Off -->
        <div class="carousel-item active">
            <img src="images\offer1.jpg" class="d-block w-100" alt="Buy 2 Jerseys, Get 20% Off" style="height: 300px; object-fit: cover;">
            <div class="carousel-caption d-none d-md-block">
               
            </div>
        </div>

        <!-- Offer 2: 10% Off on Selected Jerseys -->
        <div class="carousel-item">
            <img src="images\big_sale.jpg" class="d-block w-100" alt="10% Off Selected Jerseys" style="height: 300px; object-fit: cover;">
            <div class="carousel-caption d-none d-md-block">  
            </div>
        </div>
        <!-- Offer 3: Free Shipping on Orders Over KSH 5,000 -->
        <div class="carousel-item">
            <img src="images\free_shipping.jpg" class="d-block w-100" alt="Free Shipping on Orders Over KSH 5,000" style="height: 300px; object-fit: cover;">
            <div class="carousel-caption d-none d-md-block">
            </div>
        </div>
    </div>
    <!-- Carousel controls -->
    <a class="carousel-control-prev" href="#offersCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#offersCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<!-- Main content -->
<div class="container mt-5">
    <div class="row">
        <!-- Sales statistics section -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Total Sales Today</h3>
                    <p class="card-text">We have made <strong><?php echo $total_sales; ?></strong> sales so far today!</p>
                </div>
            </div>
        </div>

        <!-- Welcome content -->
        <div class="col-md-6">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Welcome to Urban Jerseys Store</h3>
            <p class="card-text">Find your favorite jerseys and enjoy exclusive offers.</p>
            <?php if ($is_logged_in): ?>
    <a href="products.php" class="btn btn-primary">Shop Now</a>
<?php else: ?>
    <a href="login.php" class="btn btn-primary">Shop Now</a>
<?php endif; ?>

        </div>
    </div>
</div>

<div class="container mt-5">
    <h3 class="text-center">New Products</h3>

    <div id="newProductsCarousel" class="carousel slide" data-ride="carousel" data-interval="3000"> <!-- 3 seconds between slides -->
        <div class="carousel-inner">
            <?php 
            $count = 0; // Initialize count for tracking first item
            while ($product = mysqli_fetch_assoc($new_products_result)): 
                // Start a new carousel item every 4 products
                if ($count % 4 == 0): ?>
                    <div class="carousel-item <?php echo ($count == 0) ? 'active' : ''; ?>">
                        <div class="row">
                <?php endif; ?>
                
                <div class="col-md-3"> <!-- Keep col-md-3 for smaller cards -->
                    <div class="card product-card mb-4"> <!-- Added 'product-card' class -->
                        <img src="images/<?php echo $product['image_url']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>" style="height: 150px; object-fit: cover;"> <!-- Adjusted height -->
                        <div class="card-body">
                            <h5 class="card-title" style="font-size: 1.1rem;"><?php echo $product['name']; ?></h5>
                            <p class="card-text"><strong>Price:Ksh <?php echo number_format($product['price'], 2); ?></strong></p>
                            <span class="badge badge-success">New</span>
                            <?php
                            // Random discount generation
                            $discount_percentage = rand(10, 50); // Generate a random discount between 10% and 50%
                            ?>
                            <span class="badge badge-warning"><?php echo $discount_percentage; ?>% Off</span>
                            <br><br>
                            <a href="product_page.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">View Product</a>
                        </div>
                    </div>
                </div>

                <?php 
                $count++;
                // Close the carousel item after 4 products
                if ($count % 4 == 0 || $count == mysqli_num_rows($new_products_result)): ?>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endwhile; ?>
        </div>

        <!-- Carousel controls -->
        <a class="carousel-control-prev" href="#newProductsCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#newProductsCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>


<div class="container mt-5">
    <h3 class="text-center">Our Jerseys</h3>
    <div class="row">
        <?php while ($product = mysqli_fetch_assoc($main_products_result)): ?>
            <div class="col-md-3"> <!-- Changed to col-md-3 for smaller cards, like in New Products -->
                <div class="card product-card mb-4"> <!-- Added 'product-card' class for consistency -->
                    <img src="images/<?php echo $product['image_url']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>" style="height: 150px; object-fit: cover;"> <!-- Adjusted height to 150px for consistency -->
                    <div class="card-body">
                    <h5 class="card-title" style="font-size: 1.1rem;"><?php echo $product['name']; ?></h5> <!-- Font size adjusted for consistency -->
                            <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p> 
                            <p class="card-text"><strong>Price:Ksh <?php echo number_format($product['price'], 2); ?></strong></p>
                            <a href="product_page.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">View Product</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel">Your Cart</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if (!empty($_SESSION['cart'])): ?>
                    <ul class="list-group">
                        <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo $item['name']; ?></strong><br>
                                    <small>KSH <?php echo number_format($item['price'], 2); ?></small><br>
                                    <span>Quantity: <?php echo $item['quantity']; ?></span>
                                </div>
                                <button class="btn btn-danger btn-sm" onclick="removeFromCart(<?php echo $index; ?>)">Remove</button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Your cart is empty!</p>
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
    // Send an AJAX request to remove the item from the cart
    $.ajax({
        url: 'remove_from_cart.php', // Path to your PHP file
        type: 'POST',
        data: { index: index },
        success: function(response) {
            // Parse JSON response
            var result = JSON.parse(response);
            if (result.success) {
                // Refresh the cart count and the modal
                location.reload(); // Reloads the page to update the cart
            } else {
                alert('Failed to remove item from cart.');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error removing item from cart:", error);
        }
    });
}
</script>

<script>
function addToCart(productId) {
    $.ajax({
        type: 'POST',
        url: 'add_to_cart.php',
        data: { id: productId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Update cart icon or any other UI element
                $('#cart-count').text(response.total_cart_items);
                alert(response.message); // Optionally show success message
            } else {
                alert(response.message); // Show error message
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            alert('An error occurred while adding the product to the cart.');
        }
    });
}

// Example button click handler
$(document).on('click', '.add-to-cart-btn', function() {
    const productId = $(this).data('id'); // Assuming the button has data-id attribute
    addToCart(productId);
});

</script>
<?php include 'footer.php'; ?>
</body>
</html>
