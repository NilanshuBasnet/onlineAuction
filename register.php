<?php
session_start();
header("Content-Type: text/plain");

$firstName = $_POST['firstName'];
$surname = $_POST['surname'];
$email = $_POST['email'];
$password = $_POST['password'];

$xmlFile = 'customers.xml';

// Check if the XML file exists
if (!file_exists($xmlFile)) {
    $xml = new DOMDocument('1.0', 'UTF-8');
    $root = $xml->createElement('customers');
    $xml->appendChild($root);
    $xml->save($xmlFile);
} else {
    $xml = new DOMDocument();
    $xml->load($xmlFile);
}

$xpath = new DOMXPath($xml);
$existingCustomer = $xpath->query("/customers/customer[email='$email']");

if ($existingCustomer->length > 0) {
    echo "This email is already registered.";
} else {
    // Generate a unique customer ID
    $customerId = generateUniqueID($xml);
    
    $customer = $xml->createElement('customer');

    $idElement = $xml->createElement('id', $customerId);
    $customer->appendChild($idElement);

    $firstNameElement = $xml->createElement('firstName', htmlspecialchars($firstName));
    $customer->appendChild($firstNameElement);

    $surnameElement = $xml->createElement('surname', htmlspecialchars($surname));
    $customer->appendChild($surnameElement);

    $emailElement = $xml->createElement('email', htmlspecialchars($email));
    $customer->appendChild($emailElement);

    $passwordElement = $xml->createElement('password', htmlspecialchars($password)); // Ideally, use password hashing
    $customer->appendChild($passwordElement);

    $xml->getElementsByTagName('customers')->item(0)->appendChild($customer);
    $xml->formatOutput = true;  // Format the output with indentation and new lines
    $xml->save($xmlFile);

    $_SESSION['customer_id'] = $customerId;
    $_SESSION['first_name'] = $firstName;
    $_SESSION['surname'] = $surname;
    $_SESSION['email'] = $email;
    $_SESSION['user_type'] = 'customer';

    echo "Registration successful!";
}

// Function to generate a unique customer ID of 5 characters with letters, numbers, and symbols
function generateUniqueID($xml) {
    $idExists = true;
    $uniqueID = '';

    $xpath = new DOMXPath($xml);

    while ($idExists) {
        // Generate a new unique ID
        $uniqueID = generateRandomString(5);

        // Check if this ID already exists in the XML
        $existingIDs = $xpath->query("/customers/customer[id='$uniqueID']");

        if ($existingIDs->length === 0) {
            $idExists = false;
        }
    }

    return $uniqueID;
}

// Function to generate a random string of specified length
function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>
