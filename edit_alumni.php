<?php
session_start();
include 'connect.php';

$conn = connect(); // Establish the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $alumni_id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM `2024-2025` WHERE al_id_no = '$alumni_id'");

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $alumni = mysqli_fetch_assoc($result);
} else {
    die("No alumni ID provided.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update alumni information
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $middle_initial = $_POST['middle_initial'];
    $department = $_POST['department'];
    $program = $_POST['program'];
    $year_graduated = $_POST['year_graduated'];
    $contact_number = $_POST['contact_number'];
    $personal_email = $_POST['personal_email'];
    $working_status = $_POST['working_status'];

    $update_query = "UPDATE `2024-2025` SET 
        last_name='$last_name', 
        first_name='$first_name', 
        middle_initial='$middle_initial', 
        department='$department', 
        program='$program', 
        year_graduated='$year_graduated', 
        contact_number='$contact_number', 
        personal_email='$personal_email', 
        working_status='$working_status' 
        WHERE al_id_no='$alumni_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: dashboard.php");
        exit();
    } else {
        die("Update failed: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Alumni</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>

<div class="w3-container">
    <h2>Edit Alumni Information</h2>

    <a href="logout.php" class="w3-button w3-red w3-round-large">Logout</a>

    <form method="POST">
        <label>Last Name</label>
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($alumni['last_name']); ?>" required class="w3-input">
        
        <label>First Name</label>
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($alumni['first_name']); ?>" required class="w3-input">
        
        <label>Middle Initial</label>
        <input type="text" name="middle_initial" value="<?php echo htmlspecialchars($alumni['middle_initial']); ?>" required class="w3-input">
        
        <label>Department</label>
        <input type="text" name="department" value="<?php echo htmlspecialchars($alumni['department']); ?>" required class="w3-input">
        
        <label>Program</label>
        <input type="text" name="program" value="<?php echo htmlspecialchars($alumni['program']); ?>" required class="w3-input">
        
        <label>Year Graduated</label>
        <input type="text" name="year_graduated" value="<?php echo htmlspecialchars($alumni['year_graduated']); ?>" required class="w3-input">
        
        <label>Contact Number</label>
        <input type="text" name="contact_number" value="<?php echo htmlspecialchars($alumni['contact_number']); ?>" required class="w3-input">
        
        <label>Personal Email</label>
        <input type="email" name="personal_email" value="<?php echo htmlspecialchars($alumni['personal_email']); ?>" required class="w3-input">
        
        <label>Working Status</label>
        <select name="working_status" required class="w3-select">
            <option value="" disabled>Select</option>
            <option value="Employed" <?php echo ($alumni['working_status'] == 'Employed') ? 'selected' : ''; ?>>Employed</option>
            <option value="Unemployed" <?php echo ($alumni['working_status'] == 'Unemployed') ? 'selected' : ''; ?>>Unemployed</option>
            <option value="Unemployed" <?php echo ($alumni['working_status'] == 'Selfemployed') ? 'selected' : ''; ?>>Selfemployed</option>
            <option value="Unemployed" <?php echo ($alumni['working_status'] == 'Still look for a Job') ? 'selected' : ''; ?>>Still look for a Job</option>
            <option value="Unemployed" <?php echo ($alumni['working_status'] == 'Never been employed') ? 'selected' : ''; ?>>Never been employed</option>
        </select>

        <button type="submit" class="w3-button w3-blue">Update Alumni</button>
    </form>
</div>

</body>
</html>
