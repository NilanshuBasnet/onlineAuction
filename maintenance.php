<?php
session_start();

    // Check if the user is logged in and has an admin type
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
        // If not an admin, display an access denied message
        echo '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Access Denied</title>
            <link rel="stylesheet" type="text/css" href="style.css">
        </head>
        <body>
            <div class="main-container">
                <h2 style= "color:red;">Access Denied</h2>
                <p>You do not have permission to view this page.<br><a href="login.html" class="bottom-link"> Login</a> to the system again to continue!</p>
            </div>
        </body>
        </html>';
        // Clear all session variables
        session_unset();

        // Destroy the session
        session_destroy();
        exit(); // Stop further execution of the script
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Maintenance Page</title>
    <script src="maintenance.js"></script>
</head>
<body>
    <h2>Maintenance Functions</h2>
    <button onclick="processItems()">Process Auction Items</button>
    <button onclick="generateReport()">Generate Report</button>

    <div id="result"></div>
</body>
</html>
