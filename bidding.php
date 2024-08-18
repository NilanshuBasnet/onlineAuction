<?php
/**  Author: Nilanshu Basnet
 *StudentID: 104346575
 *Main Function: Processes bid submissions, updating the auction XML file with new bid details and handling validation checks.
*/ 
session_start();

// Check if the user is logged in by verifying if the session is set
if (!isset($_SESSION['customer_id'])) {
    // If the session is not set, redirect to login page
    header("Location: login.html");
    exit(); // Stop further execution of the script
}

// Check if the customer type is admin
$isAdmin = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
// If the session is set, the user is logged in, and you can proceed with displaying the bidding page content

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bidding Page</title>
    <link rel="stylesheet" type="text/css" href="style/pagestyle.css">
    <script src="bidding.js"></script>
</head>
<body>
    <?php
    // Display the message if it exists
    if (isset($_SESSION['message'])) {
        echo '<div class="registrationSuccess"><p>' . $_SESSION['message'] . '</p></div>';
        unset($_SESSION['message']); // Clear the message after displaying
    }
    ?>
    <div class="navigation">
    <div class="image-column">
        <img src="Asset/shoponline.png" alt="ShopOnline Logo">
    </div>
    <div class="links-column">
        <select class="menu" name="shoppages" id="shoppages" onchange="location = this.value;">
            <option value="bidding.php" selected>Bidding</option>
            <option value="listing.php">Listing</option>
            <?php if ($isAdmin): ?>
                <option value="maintenance.php">Maintenance</option>
                <option value="deleteItems.php">Manage Auction</option>
            <?php endif; ?>
            <option value="history.php">History</option>
        </select>
    </div>
    </div>
    <h2>Items Available for Bidding</h2>
    <div id="itemsList"></div>
    <a href="logout.php" style="text-decoration:none;"><button class="logout-button">Logout</button></a>
    
</body>
</html>

