<?php
session_start();
include 'php/db.php'; // Ensure the database connection is included

function getJerseysByLeague($league) {
    global $conn;
    $sql = "SELECT * FROM products WHERE category = 'Jerseys' AND league = '$league'";
    $result = mysqli_query($conn, $sql);
    return $result;
}

$league = isset($_GET['league']) ? $_GET['league'] : 'epl'; // Default to EPL
$jerseys = getJerseysByLeague($league);

$jersey_data = [];
while ($row = mysqli_fetch_assoc($jerseys)) {
    $jersey_data[] = $row;
}

echo json_encode($jersey_data);
?>
