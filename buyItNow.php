<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
    echo "User not logged in.";
    exit();
}

$itemID = $_POST['itemID'];
$customerID = $_SESSION['customer_id'];
$xmlFile = 'auction.xml';

$xml = new DOMDocument();
$xml->load($xmlFile);

$xpath = new DOMXPath($xml);
$items = $xpath->query("/items/item[itemID='$itemID' and status='in_progress']");

if ($items->length > 0) {
    $item = $items->item(0);
    $buyItNowPrice = $item->getElementsByTagName('buyItNowPrice')->item(0)->nodeValue;
    
    // Update the item details
    $item->getElementsByTagName('bidPrice')->item(0)->nodeValue = $buyItNowPrice;
    $item->getElementsByTagName('bidderID')->item(0)->nodeValue = $customerID;
    $item->getElementsByTagName('status')->item(0)->nodeValue = 'sold';

    $xml->save($xmlFile);
    echo "Thank you for purchasing this item.";
} else {
    echo "Item not available or already sold.";
}
?>
