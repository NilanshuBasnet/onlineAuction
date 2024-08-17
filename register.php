<!-- 
 Author: Nilanshu Basnet
 StudentID: 104346575
 Main Function:  Manages user registration, including adding new users to the XML file and handling session setup.-->

<?php
session_start();
header("Content-Type: text/plain");

$firstName = $_POST['firstName'];
$surname = $_POST['surname'];
$email = $_POST['email'];
$password = $_POST['password'];

$xmlFile = '../data/customers.xml';

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
    
    
    // Manually format the XML string
    $xmlContent = $xml->saveXML();
    $formattedXmlContent = formatXmlString($xmlContent);
    
    // Save the formatted XML string to the file
    file_put_contents($xmlFile, $formattedXmlContent);

    $_SESSION['customer_id'] = $customerId;
    $_SESSION['first_name'] = $firstName;
    $_SESSION['surname'] = $surname;
    $_SESSION['email'] = $email;
    $_SESSION['user_type'] = 'customer';

    //Remove or comment out this part when sending email
    $_SESSION['message'] = 'Registration successful!'; //I have included this and commented the email sending part as it was making the page slow
   
   /*  // Prepare email
    $to = $email;
    $subject = "Welcome to ShopOnline!";
    $message = "Dear $firstName,\n\nWelcome to use ShopOnline! Your customer id is $customerId and the password is $password.\n";
    $headers = "From: registration@shoponline.com.au\r\n";
    $headers .= "Reply-To: registration@shoponline.com.au\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Send email
    if (mail($to, $subject, $message, $headers)) {
        $_SESSION['message'] = "Registration Successful! Login Details sent to your email address";
    } else {
        $_SESSION['message'] = "Registration Successful! Email not sent from XAMPP.";
    }*/

    echo $_SESSION['message'];
}


function formatXmlString($xmlString) {
    $dom = new DOMDocument();
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xmlString);
    return $dom->saveXML();
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
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>
