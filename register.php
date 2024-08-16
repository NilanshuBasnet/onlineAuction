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
    $customerId = uniqid();
    
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
?>
