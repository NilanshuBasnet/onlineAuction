<!-- 
 Author: Nilanshu Basnet
 StudentID: 104346575
 Main Function: Processes auction items to determine their final status based on bidding and reserve price.-->

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

// Set the timezone to Sydney (or your preferred timezone)
date_default_timezone_set('Australia/Sydney');

$xmlFile = '../data/auction.xml';
$xml = simplexml_load_file($xmlFile);

$currentDate = new DateTime();
$processedItems = 0;

foreach ($xml->item as $item) {
    if ((string)$item->status == 'in_progress') {
        $endDate = new DateTime($item->duration);

        if ($currentDate >= $endDate) {
            if ((float)$item->bidPrice >= (float)$item->reservePrice) {
                $item->status = 'sold';
            } else {
                $item->status = 'failed';
            }
            $processedItems++;
        }
    }
}


$xml->asXML($xmlFile);
echo "Processing complete. $processedItems items processed.";
?>
