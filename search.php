<?php
include 'header.php';  // Include the header
// The rest of your page content goes here
?>

<?php
include 'php/db.php'; // Include the database connection

$search_query = ''; // Initialize variable to avoid undefined variable notice

if (isset($_GET['query'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['query']);

    // Search query to fetch results matching the search term
    $query = "SELECT * FROM products WHERE name LIKE '%$search_query%' OR description LIKE '%$search_query%'";
    $result = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Link to CSS file -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<main class="container mt-5">
    <div class="row">
        <?php if (isset($result) && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="images/<?php echo htmlspecialchars($row['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>" style="max-height: 250px; width: 100%; object-fit: contain;"> <!-- Adjusted image sizing -->
                        <div class="card-body text-center"> <!-- Centering content -->
                            <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                            <p class="card-text"><strong>Price: Ksh <?php echo htmlspecialchars($row['price']); ?></strong></p>
                            <a href="product_page.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View Product</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No results found for your search.</p> <!-- Updated message -->
        <?php endif; ?>
    </div>
</main>


    <footer class="text-center mt-5">
        <p>&copy; <?php echo date('Y'); ?> Urban Jerseys Store</p>
    </footer>

    <!-- Include FontAwesome for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>
</html>
