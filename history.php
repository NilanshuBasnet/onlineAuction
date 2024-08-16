<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bids</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>My Bids</h2>

    <?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['customer_id'])) {
        echo "<p class='error'>You must be logged in to view your bids.</p>";
        exit();
    }

    $customerID = $_SESSION['customer_id'];
    $xmlFile = 'auction.xml';

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
</body>
</html>
