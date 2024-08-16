<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List Item for Auction</title>
    <style>
        #otherCategoryField {
            display: none;
        }
    </style>
</head>
<body>
    <h2>List an Item for Auction</h2>
    <form id="listingForm">
        <label>Item Name:</label>
        <input type="text" id="itemName" name="itemName" required><br>

        <label>Category:</label>
        <select id="category" name="category" onchange="toggleOtherCategoryField()">
            <!-- Existing categories from auction.xml will be dynamically populated -->
            <option value="Other">Other</option>
        </select><br>

        <div id="otherCategoryField">
            <label>New Category:</label>
            <input type="text" id="newCategory" name="newCategory"><br>
        </div>

        <label>Description:</label>
        <input type="text" id="description" name="description" required><br>

        <label>Starting Price:</label>
        <input type="number" id="startingPrice" name="startingPrice" required><br>

        <label>Reserve Price:</label>
        <input type="number" id="reservePrice" name="reservePrice" required><br>

        <label>Buy-It-Now Price:</label>
        <input type="number" id="buyItNowPrice" name="buyItNowPrice" required><br>

        <label>Duration:</label><br>
        <label>Days:</label>
        <input type="number" id="days" name="days" required value="0"><br>

        <label>Hours:</label>
        <select id="hours" name="hours">
            <option value="0" selected>Hours</option>
            <?php
            for ($i = 0; $i < 24; $i++) {
                echo "<option value='$i'>$i</option>";
            }
            ?>
        </select><br>

        <label>Minutes:</label>
        <select id="minutes" name="minutes">
            <option value="0" selected>Minutes</option>
            <?php
            for ($i = 0; $i < 60; $i++) {
                echo "<option value='$i'>$i</option>";
            }
            ?>
        </select><br>

        <input type="button" value="List Item" onclick="listItem()">
    </form>
    <div id="result"></div>

    <script src="listItem.js"></script>
</body>
</html>
