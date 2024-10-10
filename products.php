<?php
include 'header.php';  // Include the header
// The rest of your page content goes here
?>
<?php

include 'php/db.php'; // Include the database connection

// Check if a product ID is provided via GET
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Sanitize the input to prevent SQL injection
    $product_id = mysqli_real_escape_string($conn, $product_id);

    // Fetch product details from the database based on the product ID
    $query = "SELECT * FROM products WHERE id = '$product_id'";
    $result = mysqli_query($conn, $query);

    // Check if the product exists
    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "Product not found!";
        exit;
    }
} else {
    // If no product ID is provided, fetch all products
    $query = "SELECT * FROM products";
    $result = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags and Bootstrap CSS -->
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Link to your CSS file -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

    <style>
        /* Additional styling for the product detail page */
        .product-detail {
            margin-top: 50px;
        }
        .product-image {
            max-width: 100%;
        }
        .quantity-input {
            width: 60px;
            text-align: center;
        }
        .btn-back {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container product-list"> 
    <?php if (isset($product)): ?>
        <!-- Display individual product details if a specific ID is provided -->
        <div class="row product-detail">
            <div class="col-md-6">
                <img src="images/<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" class="product-image img-fluid" style="max-height: 400px; width: 100%; object-fit: contain;"> <!-- Increased max-height -->
            </div>
            <div class="col-md-6">
                <h2><?php echo $product['name']; ?></h2>
                <p><strong>Price:</strong> Ksh <?php echo number_format($product['price'], 2); ?></p>
                <p><?php echo $product['description']; ?></p>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" class="form-control quantity-input" value="1" min="1">
                </div>
                <button class="btn btn-success" id="add-to-cart-btn" data-id="<?php echo $product['id']; ?>">Add to Cart</button>
                <div class="btn-back">
                    <a href="products.php" class="btn btn-secondary">Back to Products</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Display all products if no specific ID is provided -->
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4"> <!-- Changed to col-md-4 for slightly larger cards -->
                    <div class="card mb-4">
                        <img src="images/<?php echo $row['image_url']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>" style="max-height: 250px; width: 100%; object-fit: contain;"> <!-- Increased max-height -->
                        <div class="card-body text-center"> <!-- Center the content of card-body -->
                            <h5 class="card-title" style="font-size: 1.1rem;"><?php echo $row['name']; ?></h5> <!-- Font size adjusted for consistency -->
                            <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p> 
                            <p class="card-text"><strong>Price: Ksh<?php echo number_format($row['price'], 2); ?></strong></p>
                            <a href="product_page.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">View Product</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>



    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
