<?php
/**
 *Author: Nilanshu Basnet
 *StudentID: 104346575
 *Main Function: Handles the submission of bids for auction items, updating the item details and bid information.
 */
session_start();

$xmlFile = '../data/auction.xml';
$xml = simplexml_load_file($xmlFile);

$itemID = $_POST['itemID'];
$bidPrice = $_POST['bidPrice'];
$customerID = $_SESSION['customer_id'];

foreach ($xml->item as $item) {
    if ((string)$item->itemID === $itemID) {
        if ($item->status != 'in_progress' || $bidPrice <= $item->bidPrice) {
            echo "Sorry, your bid is not valid.";
            exit;
        }

        $item->bidPrice = $bidPrice;
        $item->bidderID = $customerID;
        $xml->asXML($xmlFile);
        echo "Thank you! Your bid is recorded in ShopOnline.";
        exit;
    }
}

echo "Item not found.";
?>