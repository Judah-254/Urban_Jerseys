<?php
session_start();
include 'php/db.php'; // Ensure the database connection is included

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    // Prepare SQL statement with a wildcard search
    $stmt = $conn->prepare("SELECT id, name FROM products WHERE name LIKE ?");
    $likeQuery = "%$query%";
    $stmt->bind_param("s", $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the query returned any results
    if ($result->num_rows > 0) {
        echo '<ul class="list-group">';
        while ($row = $result->fetch_assoc()) {
            // Display product names in the list, with product IDs for redirection
            echo '<li class="list-group-item suggestion-item" data-id="' . htmlspecialchars($row['id']) . '">'
                . htmlspecialchars($row['name']) . '</li>';
        }
        echo '</ul>';
    } else {
        // If no results are found
        echo '<p>No results found</p>';
    }
} else {
    // If the query parameter is missing
    echo '<p>No query provided</p>';
}

// Close the statement and the connection
$stmt->close();
$conn->close();
?>
