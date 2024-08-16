<?php
session_start();

function constructDuration($days, $hours, $minutes) {
    $durationToAdd = "P" . $days . "D" . "T" . $hours . "H" . $minutes . "M";
    $result = new DateTime(date('Y-m-d H:i:s'));
    $result->add(new DateInterval($durationToAdd));
    return $result->format('Y-m-d H:i:s');
}

// Set the default timezone to Sydney, Australia
date_default_timezone_set('Australia/Sydney');

// Load the auction XML file or create a new one if it doesn't exist
$xmlFile = 'auction.xml';
if (!file_exists($xmlFile)) {
    $xml = new DOMDocument('1.0', 'UTF-8');
    $xml->formatOutput = true;
    $root = $xml->createElement('items');
    $xml->appendChild($root);
    $xml->save($xmlFile);
} else {
    $xml = new DOMDocument();
    $xml->load($xmlFile);
}

// Get form data
$customerID = $_SESSION['customer_id'];  // Assuming the customer is logged in
$itemName = $_POST['itemName'];
$category = $_POST['category'];
$newCategory = $_POST['newCategory'] ?? '';
$description = $_POST['description'];
$startingPrice = $_POST['startingPrice'];
$reservePrice = $_POST['reservePrice'];
$buyItNowPrice = $_POST['buyItNowPrice'];
$days = $_POST['days'];
$hours = $_POST['hours'];
$minutes = $_POST['minutes'];

// Set startingPrice to 0 if it is empty
$startingPrice = empty($startingPrice) ? 0 : $startingPrice;

// Use new category if "Other" is selected
if ($category === 'Other' && !empty($newCategory)) {
    $category = $newCategory;
}

// Validate the input
if ($startingPrice > $reservePrice || $reservePrice >= $buyItNowPrice) {
    echo "<p class='errormsg'>Invalid price settings.</p>";
    exit;
}

// Generate unique itemID
$itemID = generateUniqueItemID($xml);

// Current date and time
$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');

// Calculate end date
$endDate = constructDuration($days, $hours, $minutes);

// Add the item to auction.xml
$itemsElement = $xml->getElementsByTagName('items')->item(0);

$itemElement = $xml->createElement('item');
$itemElement->appendChild($xml->createElement('customerID', $customerID));
$itemElement->appendChild($xml->createElement('itemID', $itemID));
$itemElement->appendChild($xml->createElement('itemName', $itemName));
$itemElement->appendChild($xml->createElement('category', $category));
$itemElement->appendChild($xml->createElement('description', $description));
$itemElement->appendChild($xml->createElement('startingPrice', $startingPrice));
$itemElement->appendChild($xml->createElement('reservePrice', $reservePrice));
$itemElement->appendChild($xml->createElement('buyItNowPrice', $buyItNowPrice));
$itemElement->appendChild($xml->createElement('bidPrice', $startingPrice));
$itemElement->appendChild($xml->createElement('duration', $endDate));
$itemElement->appendChild($xml->createElement('status', 'in_progress'));
$itemElement->appendChild($xml->createElement('startDate', $currentDate));
$itemElement->appendChild($xml->createElement('startTime', $currentTime));
$itemElement->appendChild($xml->createElement('bidderID', 'None'));

$itemsElement->appendChild($itemElement);
$xml->save($xmlFile);

// Return the item number, date, and time
echo "<p class='successmsg'>Thank you! Your item has been listed in ShopOnline. The item number is <b>$itemID</b>, and the bidding starts now: <b>$currentTime</b> on <b>$currentDate</b>.</p>";

// Function to generate a unique itemID of 6 characters
function generateUniqueItemID($xml) {
    $idExists = true;
    $uniqueID = '';

    $xpath = new DOMXPath($xml);

    while ($idExists) {
        // Generate a new unique ID
        $uniqueID = generateRandomItemID();

        // Check if this ID already exists in the XML
        $existingIDs = $xpath->query("/items/item/itemID[text()='$uniqueID']");

        if ($existingIDs->length === 0) {
            $idExists = false;
        }
    }

    return $uniqueID;
}

// Function to generate a random itemID of 6 characters
function generateRandomItemID() {
    $capitalLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $smallLetters = 'abcdefghijklmnopqrstuvwxyz';
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz' . $capitalLetters . '!@#$%^&*()_+-=';
    $charactersLength = strlen($characters);

    // Generate the first two characters: one capital letter and one small letter
    $firstChar = $capitalLetters[rand(0, strlen($capitalLetters) - 1)];
    $secondChar = $smallLetters[rand(0, strlen($smallLetters) - 1)];

    // Generate the remaining 4 characters
    $remainingChars = '';
    for ($i = 0; $i < 4; $i++) {
        $remainingChars .= $characters[rand(0, $charactersLength - 1)];
    }

    return $firstChar . $secondChar . $remainingChars;
}
?>
