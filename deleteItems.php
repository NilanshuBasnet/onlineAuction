<!-- 
 Author: Nilanshu Basnet
 StudentID: 104346575
 Main Function: Provides functionality for admins to delete auction items from the system.-->

<?php
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

// Check if the customer type is admin
$isAdmin = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';

// Define the path to the XML file
$xmlFile = '../data/auction.xml';

// Initialize variables for search results
$itemsFound = false;
$noResults = false;

// Check if the XML file exists
if (!file_exists($xmlFile)) {
    $fileNotFound = true;
} else {
    // Load and parse the XML file
    $xml = simplexml_load_file($xmlFile);

    if (isset($_POST['delete'])) {
        $itemIDToDelete = $_POST['itemID'];
        
        // Create a new DOMDocument instance to manipulate XML
        $dom = new DOMDocument();
        $dom->load($xmlFile);
        $xpath = new DOMXPath($dom);

        // Find the item to delete
        $items = $xpath->query("//item[itemID='$itemIDToDelete']");
        if ($items->length > 0) {
            $item = $items->item(0);
            $item->parentNode->removeChild($item);
            $dom->save($xmlFile);
        }

        // Reload the page to reflect changes
        header("Location: deleteItems.php");
        exit();
    }

    if (isset($_POST['search'])) {
        $searchTerm = trim($_POST['searchTerm']);
        
        // Load and parse the XML file
        $xml = simplexml_load_file($xmlFile);
        $filteredItems = [];
        
        if ($searchTerm) {
            // Filter items by itemID
            foreach ($xml->item as $item) {
                if ((string)$item->itemID === $searchTerm) {
                    $filteredItems[] = $item;
                    $itemsFound = true;
                }
            }
            if (empty($filteredItems)) {
                $noResults = true;
            }
        } else {
            // No search term provided, show all items
            $filteredItems = $xml->item;
            $itemsFound = true;
        }
    } else {
        // Display all items by default
        $filteredItems = $xml->item;
        $itemsFound = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Display Items</title>
    <link rel="stylesheet" type="text/css" href="style/pagestyle.css">
</head>
<body>
    <div class="navigation">
        <div class="image-column">
            <img src="shoponline.png" alt="ShopOnline Logo">
        </div>
        <div class="links-column">
            <select class="menu" name="shoppages" id="shoppages" onchange="location = this.value;">
                <option value="bidding.php">Bidding</option>
                <option value="listing.php">Listing</option>
                <?php if ($isAdmin): ?>
                    <option value="maintenance.php">Maintenance</option>
                    <option value="deleteItems.php" selected>Manage Auction</option>
                <?php endif; ?>
                <option value="history.php">History</option>
            </select>
        </div>
    </div>

    <h1>Items in Auction</h1>

    <form method="post" action="deleteItems.php">
        <input class="searchBox" type="text" name="searchTerm" placeholder="Enter Item ID" value="<?php echo isset($_POST['searchTerm']) ? htmlspecialchars($_POST['searchTerm']) : ''; ?>">
        <input class="action-button" type="submit" name="search" value="Search">
    </form>

    <?php if (isset($fileNotFound) && $fileNotFound): ?>
        <p>File not found: <?php echo htmlspecialchars($xmlFile); ?></p>
    <?php elseif ($itemsFound): ?>
        <table border="1">
            <tr>
                <th>Customer ID</th>
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Starting Price</th>
                <th>Reserve Price</th>
                <th>Buy It Now Price</th>
                <th>Bid Price</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Bidder ID</th>
                <th>Action</th>
            </tr>
            <?php foreach ($filteredItems as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item->customerID); ?></td>
                    <td><?php echo htmlspecialchars($item->itemID); ?></td>
                    <td><?php echo htmlspecialchars($item->itemName); ?></td>
                    <td><?php echo htmlspecialchars($item->category); ?></td>
                    <td><?php echo htmlspecialchars($item->startingPrice); ?></td>
                    <td><?php echo htmlspecialchars($item->reservePrice); ?></td>
                    <td><?php echo htmlspecialchars($item->buyItNowPrice); ?></td>
                    <td><?php echo htmlspecialchars($item->bidPrice); ?></td>
                    <td><?php echo htmlspecialchars($item->duration); ?></td>
                    <td><?php echo htmlspecialchars($item->status); ?></td>
                    <td><?php echo htmlspecialchars($item->bidderID); ?></td>
                    <td>
                        <form method="post" action="deleteItems.php" style="display:inline;">
                            <input type="hidden" name="itemID" value="<?php echo htmlspecialchars($item->itemID); ?>">
                            <input type="submit" name="delete" value="Delete">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif ($noResults): ?>
        <p>No items found with the specified ID.</p>
    <?php else: ?>
        <p>No items found.</p>
    <?php endif; ?>
    <a href="logout.php" style="text-decoration:none;"><button class="logout-button">Logout</button></a>
</body>
</html>
