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

$xmlFile = 'auction.xml';
$xml = simplexml_load_file($xmlFile);

$soldItems = [];
$failedItems = [];
$totalRevenue = 0;

foreach ($xml->item as $key => $item) {
    if ((string)$item->status == 'sold') {
        $revenue = 0.03 * $item->bidPrice;
        $totalRevenue += $revenue;
        $soldItems[] = $item;
        unset($xml->item[$key]);
    } elseif ((string)$item->status == 'failed') {
        $revenue = 0.01 * $item->reservePrice;
        $totalRevenue += $revenue;
        $failedItems[] = $item;
        unset($xml->item[$key]);
    }
}

$xml->asXML($xmlFile);

// Create a new XML document to hold the report data
$reportXml = new SimpleXMLElement('<report></report>');

$soldItemsXml = $reportXml->addChild('soldItems');
foreach ($soldItems as $item) {
    $itemXml = $soldItemsXml->addChild('item');
    $itemXml->addChild('itemID', $item->itemID);
    $itemXml->addChild('itemName', $item->itemName);
    $itemXml->addChild('category', $item->category);
    $itemXml->addChild('startingPrice', $item->startingPrice);
    $itemXml->addChild('bidPrice', $item->bidPrice);
    $itemXml->addChild('bidderID', $item->bidderID);
    $itemXml->addChild('status', $item->status);
}

$failedItemsXml = $reportXml->addChild('failedItems');
foreach ($failedItems as $item) {
    $itemXml = $failedItemsXml->addChild('item');
    $itemXml->addChild('itemID', $item->itemID);
    $itemXml->addChild('itemName', $item->itemName);
    $itemXml->addChild('category', $item->category);
    $itemXml->addChild('startingPrice', $item->startingPrice);
    $itemXml->addChild('reservePrice', $item->reservePrice);
    $itemXml->addChild('status', $item->status);
}

$reportXml->addChild('totalRevenue', number_format($totalRevenue, 2));

// Apply XSLT transformation
$xsl = new DOMDocument;
$xsl->load('report.xsl');

$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl);

$xmlDom = new DOMDocument;
$xmlDom->loadXML($reportXml->asXML());

echo $proc->transformToXML($xmlDom);
?>
