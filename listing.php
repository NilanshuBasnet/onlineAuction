<!-- 
 Author: Nilanshu Basnet
 StudentID: 104346575
 Main Function: Displays the list of auction items available, typically fetching and showing items from the XML file. -->

<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.html");
    exit();
}

// Check if the customer type is admin
$isAdmin = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List Item for Auction</title>
    <link rel="stylesheet" type="text/css" href="style/pagestyle.css">
    <style>
        #otherCategoryField {
            display: none;
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
                <option value="listing.php"selected>Listing</option>
                <?php if ($isAdmin): ?>
                    <option value="maintenance.php">Maintenance</option>
                    <option value="deleteItems.php">Manage Auction</option>
                <?php endif; ?>
                <option value="history.php">History</option>
            </select>
        </div>
    </div>
    <h2>List an Item for Auction</h2>
    <div class="listing-container">
        <form id="listingForm">
            <input placeholder="Item Name" class="input" type="text" id="itemName" name="itemName" required><br><br>

            <label>Category:</label>
            <select class="input" id="category" name="category" onchange="toggleOtherCategoryField()">
                <!-- Existing categories from auction.xml will be dynamically populated -->
                <option value="Other">Other</option>
            </select><br><br>

            <div id="otherCategoryField">
                <input placeholder="Enter your Category" class="input" type="text" id="newCategory" name="newCategory"><br><br>
            </div>
            <input placeholder="Description" class="input" type="text" id="description" name="description" required><br><br>
            <input placeholder="Starting Price" class="input" type="number" id="startingPrice" name="startingPrice" required><br><br>
            <input placeholder="Reserve Price" class="input" type="number" id="reservePrice" name="reservePrice" required><br><br>
            <input placeholder="Buy-It-Now Price" class="input" type="number" id="buyItNowPrice" name="buyItNowPrice" required><br><br>

            <label>Duration:</label><br>
            <input placeholder="No. of Days" class="input" type="number" id="days" name="days" required value="0"><br><br>
            <select class="input" id="hours" name="hours">
                <option value="0" selected>Hours</option>
                <?php
                for ($i = 0; $i < 24; $i++) {
                    echo "<option value='$i'>$i</option>";
                }
                ?>
            </select><br><br>
            <select class="input" id="minutes" name="minutes">
                <option value="0" selected>Minutes</option>
                <?php
                for ($i = 0; $i < 60; $i++) {
                    echo "<option value='$i'>$i</option>";
                }
                ?>
            </select><br><br>

            <input class="action-button" type="button" value="List Item" onclick="listItem()">
            <button class="action-button" type="reset">Reset</button>
        </form>
        <div id="result"></div>
    </div>

    <script src="listItem.js"></script>
    <a href="logout.php" style="text-decoration:none;"><button class="logout-button">Logout</button></a>
</body>
</html>
