<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== 1) {
    header("Location: login.php");
    exit();
}

$conn = connect(); // Ensure you have a connection

if (isset($_GET['id'])) {
    $alumni_id = mysqli_real_escape_string($conn, $_GET['id']);

    $sql = "DELETE FROM `2024-2025` WHERE id='$alumni_id'";
    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard.php?success=deleted");
        exit();
    } else {
        $error = "Delete failed";
    }
} else {
    header("Location: dashboard.php");
    exit();
}

mysqli_close($conn);
?>
