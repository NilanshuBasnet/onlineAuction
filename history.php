<?php
/**
 * Author: Nilanshu Basnet
 *StudentID: 104346575
 *Main Function: Retrieves and displays a user's bidding history from the XML file.
 */
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['customer_id'])) {
        echo "<p class='error'>You must be logged in to view your bids.</p>";
        exit();
    }
    
    // Check if the customer type is admin
    $isAdmin = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bids</title>
    <link rel="stylesheet" type="text/css" href="style/pagestyle.css">
    <style>
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
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
                <?php if ($isAdmin): ?>
                    <option value="maintenance.php">Maintenance</option>
                    <option value="deleteItems.php" selected>Manage Auction</option>
                <?php endif; ?>
                <option value="history.php" selected>History</option>
            </select>
        </div>
    </div>

    <h2>My Bids</h2>

    <?php

    $customerID = $_SESSION['customer_id'];
    $xmlFile = '../data/auction.xml';

    // Check if the XML file exists
    if (!file_exists($xmlFile)) {
        echo "<p class='error'>Record not found: XML file does not exist.</p>";
        exit();
    }

    $xml = simplexml_load_file($xmlFile);

    // Initialize output
    $output = '<table>
                    <thead>
                        <tr>
                            <th>Item ID</th>
                            <th>Item Name</th>
                            <th>Bid Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>';

    // Flag to check if any matching bids are found
    $foundBid = false;

    // Loop through items and find bids matching the customer ID
    foreach ($xml->item as $item) {
        if ((string)$item->bidderID === $customerID) {
            $output .= '<tr>
                            <td>' . htmlspecialchars($item->itemID) . '</td>
                            <td>' . htmlspecialchars($item->itemName) . '</td>
                            <td>' . htmlspecialchars($item->bidPrice) . '</td>
                            <td>' . htmlspecialchars($item->status) . '</td>
                        </tr>';
            $foundBid = true;
        }
    }

    $output .= '</tbody></table>';

    // Display output
    if (!$foundBid) {
        echo "<p class='error'>Record not found: No bids found for your user ID.</p>";
    } else {
        echo $output;
    }
    ?>
    <a href="logout.php" style="text-decoration:none;"><button class="logout-button">Logout</button></a>
</body>
</html>
