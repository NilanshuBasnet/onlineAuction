<?php
/**
 *Author: Nilanshu Basnet
 *StudentID: 104346575
 *Main Function: Provides admin functionality for maintaining the system, such as processing auction items and generating reports.
 */
session_start();

// Check if the user is logged in and has an admin type
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    // Include the access denied page
    include('accessDenied.html');
    
    // Clear all session variables
    session_unset();

    // Destroy the session
    session_destroy();
    exit(); // Stop further execution of the script
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Maintenance Page</title>
    <link rel="stylesheet" type="text/css" href="style/pagestyle.css">
    <script src="maintenance.js"></script>
</head>
<body>
    <div class="navigation">
        <div class="image-column">
            <img src="Asset/shoponline.png" alt="ShopOnline Logo">
        </div>
        <div class="links-column">
            <select class="menu" name="shoppages" id="shoppages" onchange="location = this.value;">
                <option value="bidding.php">Bidding</option>
                <option value="listing.php">Listing</option>
                <option value="maintenance.php" selected>Maintenance</option>
                <option value="deleteItems.php">Manage Auction</option>
                <option value="history.php">History</option>
            </select>
        </div>
    </div>
    <h2>Maintenance Functions</h2>
    <button onclick="processItems()">Process Auction Items</button>
    <button onclick="generateReport()">Generate Report</button>

    <div id="result"></div>
    <a href="logout.php" style="text-decoration:none;"><button class="logout-button">Logout</button></a>
</body>
</html>
