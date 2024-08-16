<?php
session_start();

// Check if the user is logged in by verifying if the session is set
if (!isset($_SESSION['customer_id'])) {
    // If the session is not set, redirect to login page
    header("Location: login.html");
    exit(); // Stop further execution of the script
}

// If the session is set, the user is logged in, and you can proceed with displaying the bidding page content

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bidding Page</title>
    <script src="bidding.js"></script>
</head>
<body>
    <?php
    // Display the message if it exists
    if (isset($_SESSION['message'])) {
        echo '<p>' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']); // Clear the message after displaying
    }
    ?>
    <a href="logout.php">Logout</a>
    <a href="listing.php">Listing</a>
    <h2>Items Available for Bidding</h2>
    <div id="itemsList"></div>
</body>
</html>

