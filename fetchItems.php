<?php
session_start();

// Set the default timezone to Sydney, Australia
date_default_timezone_set('Australia/Sydney');

$xmlFile = '../data/auction.xml';
$xml = simplexml_load_file($xmlFile);

$todayDate = date('Y-m-d H:i:s');
$currentDate = new DateTime($todayDate);
$output = '';

foreach ($xml->item as $item) {
    $endDate = new DateTime($item->duration);

    // Check if the item is already marked as sold
    if ((string)$item->status === 'sold') {
        $status = 'Sold';
        $timeLeft = 'Auction Ended';
    }
    elseif((string)$item->status === 'failed') {
        $status = 'Failed';
        $timeLeft = 'Time Expired';
    } 
    elseif ($currentDate >= $endDate) {
        if ((string)$item->status === 'in_progress') {
            // Update status to 'time expired' if auction duration has passed and it's still 'in_progress'
            $item->status = 'in_progress';
            $xml->asXML($xmlFile);  // Save the XML file with the updated status
        }
        $status = 'Processing';
        $timeLeft = 'Auction Ended';
    } else {
        $status = 'In progress';
        // Calculate remaining time
        $interval = $currentDate->diff($endDate);
        $timeLeft = $interval->format('%d days %h hours %i minutes');
    }

    $output .= '<div class="itemsList">';
    $output .= '<p>Item ID: ' . $item->itemID . '</p>';
    $output .= '<p>Name: ' . $item->itemName . '</p>';
    $output .= '<p>Category: ' . $item->category . '</p>';
    $output .= '<p>Description: ' . substr($item->description, 0, 30) . '</p>';
    $output .= '<p>Buy It Now Price: ' . $item->buyItNowPrice . '</p>';
    $output .= '<p>Current Bid: ' . $item->bidPrice . '</p>';
    $output .= '<p>Time Left: ' . $timeLeft . '</p>';  // Display time left or auction ended message
    $output .= '<p>Status: ' . $status . '</p>';

    // Show buttons only if the auction is still 'In progress'
    if ($status === 'In progress') {
        $output .= '<button class="action-button" id="placeBid" onclick="placeBid(\'' . $item->itemID . '\')">Place Bid</button>';
        $output .= '<button class="action-button" id="buyNow" onclick="buyItNow(\'' . $item->itemID . '\')">Buy It Now</button>';
    }

    $output .= '</div><hr>';
}

echo $output;
?>
