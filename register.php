<?php
session_start();
include 'connect.php'; // Include the database connection file

$conn = connect(); // Initialize the connection

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Validate input fields (optional)
    if (empty($username) || empty($email) || empty($password)) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        // Check if username or email already exists
        $sql = "SELECT * FROM users WHERE username='$username' OR email='$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $error = "Username or email already exists.";
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data into the database
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
            if (mysqli_query($conn, $sql)) {
                header("Location: login.php?registration=success");
                exit();
            } else {
                $error = "Registration failed.";
            }
        }
    }
}

mysqli_close($conn); // Close the connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>

<div class="w3-container w3-center">
    <h2>Alumni Record System Registration</h2>

    <?php if (isset($error)): ?>
        <div class="w3-panel w3-red"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="" method="post" class="w3-container">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" class="w3-input w3-border w3-round-large" required>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="w3-input w3-border w3-round-large" required>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="w3-input w3-border w3-round-large" required>
        <button type="submit" name="register" class="w3-button w3-block w3-green w3-round-large">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>
</div>

</body>
</html>
