<?php
function connect() {
    $servername = "localhost"; // Your server name
    $username = "root";        // Your database username
    $password = "";            // Your database password
    $dbname = "alumni_records"; // Your database name

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}
?>