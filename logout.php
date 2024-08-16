<?php
session_start(); // Start the session

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to login.html
header("Location: login.html");
exit(); // Ensure the script stops executing after the redirect
?>
