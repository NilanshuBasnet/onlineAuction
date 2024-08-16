<?php
session_start();

$email = $_POST['email'];
$password = $_POST['password'];
$xml = simplexml_load_file('customers.xml');

// Find the customer with the provided email
$customer = null;
foreach ($xml->customer as $cust) {
    if ((string) $cust->email == $email) {
        $customer = $cust;
        break;
    }
}

// If customer found, verify password
if ($customer && $customer->password == $password) {
    $_SESSION['customer_id'] = (string) $customer->id; // Save customer id to session

    // Set user type based on email
    if ($email === 'admin@shoponline.com') {
        $_SESSION['user_type'] = 'admin'; // Set user type to 'admin'
    } else {
        $_SESSION['user_type'] = 'customer'; // Set user type to 'customer'
    }

    echo "success";
} else {
    echo '<p class="errormsg">Login failed: Invalid email or password.</p>';
}
?>
