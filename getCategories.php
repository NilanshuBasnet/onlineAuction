<!-- 
 Author: Nilanshu Basnet
 StudentID: 104346575
 Main Function: Returns a list of auction categories from the XML file in JSON format, used for populating category dropdowns.-->

<?php
$xmlFile = '../data/auction.xml';
$categories = [];

if (file_exists($xmlFile)) {
    $xml = new DOMDocument();
    $xml->load($xmlFile);

    $items = $xml->getElementsByTagName('item');
    foreach ($items as $item) {
        $category = $item->getElementsByTagName('category')->item(0)->textContent;
        if (!in_array($category, $categories) && $category !== 'Other') {
            $categories[] = $category;
        }
    }
}

header('Content-Type: application/json');
echo json_encode($categories);
?>
