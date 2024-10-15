<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: add_alumni.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alumni Record System</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        body {
            background-image: url("background.jpg"); /* Update this path */
            background-size: cover; /* Cover the entire viewport */
            background-position: center; /* Center the image */
            background-repeat: no-repeat; /* Prevent repeating */
            height: 100vh; /* Full height */
            margin: 0; /* Remove default margin */
            color: white; /* Change text color for visibility */
        }
        .w3-container {
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background for better visibility */
            padding: 20px; /* Add some padding */
            border-radius: 10px; /* Optional: rounded corners */
        }
    </style>
</head>
<body>

<div class="w3-container w3-center">
    <h2>Alumni Record System</h2>
    <p>Please login to access the dashboard.</p>
    <a href="login.php" class="w3-button w3-green w3-round-large">Login</a>
</div>

</body>
</html>
