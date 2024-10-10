<?php
include 'header.php';  // Include the header
// The rest of your page content goes here
?>
<?php

include 'php/db.php'; // Ensure the database connection is included

// Fetch EPL jerseys from the database
$epl_jerseys_query = "SELECT * FROM products WHERE league = 'EPL'";
$epl_jerseys_result = mysqli_query($conn, $epl_jerseys_query);

if (!$epl_jerseys_result) {
    echo "Error executing query: " . mysqli_error($conn);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EPL Jerseys</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Font Awesome for icons -->
</head>

<body>
    <main class="container mt-5">
        <h3 class="text-center">EPL Jerseys</h3>
        <div class="row">
            <?php while ($jersey = mysqli_fetch_assoc($epl_jerseys_result)): ?>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <img src="images/<?php echo $jersey['image_url']; ?>" class="card-img-top"
                            alt="<?php echo $jersey['name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $jersey['name']; ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($jersey['description']); ?></p>
                            <p class="card-text"><strong>Price: KSH
                                    <?php echo number_format($jersey['price'], 2); ?></strong></p>
                            <a href="product_page.php?id=<?php echo $jersey['id']; ?>" class="btn btn-primary">View
                                Product</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        </div>

        <style>
            .home-icon {
                position: fixed;
                top: 10px;
                right: 10px;
                z-index: 1000;
            }

            .home-icon a {
                font-size: 24px;
                color: #000;
                /* Adjust color as needed */
            }

            .home-icon a:hover {
                color: #007bff;
                /* Hover color */
            }
        </style>

</body>

</html>