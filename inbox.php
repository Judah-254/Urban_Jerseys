<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'php/db.php'; // Make sure this path is correct

// Fetch messages for the user
$messages = [];
$sql_messages = "SELECT id, subject, message, date_sent FROM messages WHERE user_id = {$_SESSION['user_id']} ORDER BY date_sent DESC";
$result_messages = $conn->query($sql_messages);
if ($result_messages->num_rows > 0) {
    while ($row = $result_messages->fetch_assoc()) {
        $messages[] = $row;
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
    <title>Inbox - Urban Jerseys Store</title>
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

<!-- Inbox Page Layout -->
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="account.php" class="list-group-item list-group-item-action">My Account</a>
                <a href="orders.php" class="list-group-item list-group-item-action">Orders</a>
                <a href="inbox.php" class="list-group-item list-group-item-action active">Inbox</a> <!-- Set active for Inbox -->
                <a href="vouchers.php" class="list-group-item list-group-item-action">Vouchers</a>
                <a href="saved_items.php" class="list-group-item list-group-item-action">Saved Items</a>
            </div>
        </div>

        <div class="col-md-9">
            <?php if (empty($messages)): ?>
                <div class="alert alert-info" role="alert">
                    You don't have any messages. Here you will be able to see all the messages that we send you. Stay tuned!
                </div>
            <?php else: ?>
                <div class="card mb-4">
                    <div class="card-header">
                        Your Messages
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Date Sent</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $message): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($message['subject']); ?></td>
                                        <td><?php echo htmlspecialchars($message['message']); ?></td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($message['date_sent'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Include necessary scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
