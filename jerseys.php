<?php
session_start();
include 'php/db.php'; // Ensure the database connection is included

// Fetch all jerseys from the products table
$query = "SELECT * FROM products WHERE category = 'jersey'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Jerseys</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Link to CSS file -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <header>
        <h1>Available Jerseys</h1>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="index.php">Urban Jerseys Store</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container mt-5">
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="images/<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['name']; ?></h5>
                            <p class="card-text">KSH <?php echo number_format($row['price'], 2); ?></p>
                            <a href="product_page.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer>
        <p>&copy; Your Jersey, Your Pride.</p>
    </footer>
</body>
</html>
