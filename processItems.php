<?php
session_start();

$xmlFile = 'auction.xml';
$xml = simplexml_load_file($xmlFile);

$currentDate = new DateTime();
$processedItems = 0;

foreach ($xml->item as $item) {
    if ((string)$item->status == 'in_progress') {
        $endDate = new DateTime($item->duration);
        $interval = $currentDate->diff($endDate);
        $timeLeft = $interval->format('%r%d days %h hours %i minutes');

        if ($currentDate >= $endDate) {
            if ($item->bidPrice >= $item->reservePrice) {
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
