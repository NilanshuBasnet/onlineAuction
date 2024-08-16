<?php
// Define the path to the XML file
$xmlFile = 'auction.xml';

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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Display Items</title>
</head>
<body>
    <h1>Items in Auction</h1>
    <?php if (isset($fileNotFound) && $fileNotFound): ?>
        <p>File not found: <?php echo htmlspecialchars($xmlFile); ?></p>
    <?php elseif ($xml->item): ?>
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
            <?php foreach ($xml->item as $item): ?>
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
    <?php else: ?>
        <p>No items found.</p>
    <?php endif; ?>
</body>
</html>
