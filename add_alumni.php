<?php
session_start();
include 'connect.php';

$conn = connect(); // Establish the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission for adding new alumni
if (isset($_POST['add'])) {
    $al_id_no = mysqli_real_escape_string($conn, $_POST['al_id_no']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $middle_initial = mysqli_real_escape_string($conn, $_POST['middle_initial']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $program = mysqli_real_escape_string($conn, $_POST['program']);
    $year_graduated = mysqli_real_escape_string($conn, $_POST['year_graduated']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $personal_email = mysqli_real_escape_string($conn, $_POST['personal_email']);
    
    // Get the working status from the form
    $working_status = mysqli_real_escape_string($conn, $_POST['working_status']);

    // Insert query for the alumni table
    $sql = "INSERT INTO `2024-2025` (al_id_no, first_name, last_name, middle_initial, department, program, year_graduated, contact_number, personal_email, working_status) 
            VALUES ('$al_id_no', '$first_name', '$last_name', '$middle_initial', '$department', '$program', '$year_graduated', '$contact_number', '$personal_email', '$working_status')";

    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard.php"); // Redirect to dashboard after adding
        exit();
    } else {
        $error = "Error adding alumni: " . mysqli_error($conn);
    }
}

// Handle CSV file import
if (isset($_POST['import'])) {
    if ($_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['csv_file']['tmp_name'];
        
        // Open the CSV file
        if (($handle = fopen($fileTmpPath, 'r')) !== FALSE) {
            // Skip the first line (header)
            fgetcsv($handle, 1000, "\t");
            
            while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                // Prepare data for insertion
                $al_id_no = mysqli_real_escape_string($conn, $data[0]);
                $last_name = mysqli_real_escape_string($conn, $data[1]);
                $first_name = mysqli_real_escape_string($conn, $data[2]);
                $middle_initial = mysqli_real_escape_string($conn, $data[3]);
                $department = mysqli_real_escape_string($conn, $data[4]);
                $program = mysqli_real_escape_string($conn, $data[5]);
                $year_graduated = mysqli_real_escape_string($conn, $data[6]);
                $contact_number = mysqli_real_escape_string($conn, $data[7]);
                $personal_email = mysqli_real_escape_string($conn, $data[8]);
                $working_status = mysqli_real_escape_string($conn, $data[9]); // Assuming working status is in the CSV

                // Insert query for each row
                $sql = "INSERT INTO `2024-2025` (al_id_no, first_name, last_name, middle_initial, department, program, year_graduated, contact_number, personal_email, working_status) 
                        VALUES ('$al_id_no', '$first_name', '$last_name', '$middle_initial', '$department', '$program', '$year_graduated', '$contact_number', '$personal_email', '$working_status')";

                mysqli_query($conn, $sql);
            }
            fclose($handle);
            header("Location: dashboard.php"); // Redirect to dashboard after importing
            exit();
        } else {
            $error = "Error opening the file.";
        }
    } else {
        $error = "Error uploading the file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Alumni</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
        }
        .header a {
            margin: 0 10px;
        }
        .container {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="header">
    <a href="logout.php" class="w3-button w3-red w3-round-large">Logout</a>
</div>

<div class="w3-container w3-center container">
    <h3>Import Alumni from CSV</h3>
    <form action="" method="post" enctype="multipart/form-data" class="w3-container">
        <label for="csv_file">Select CSV File:</label>
        <input type="file" name="csv_file" required class="w3-input" accept=".csv">

        <button type="submit" name="import" class="w3-button w3-blue">Import Alumni</button>
    </form>

    <?php if (isset($error)): ?>
        <div class="w3-panel w3-red"><?php echo $error; ?></div>
    <?php endif; ?>

    <h3>Add New Alumni</h3>
    <form action="" method="post" class="w3-container">
        <label for="al_id_no">Alumni ID:</label>
        <input type="text" name="al_id_no" required class="w3-input">

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" required class="w3-input">

        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" required class="w3-input">

        <label for="middle_initial">Middle Initial:</label>
        <input type="text" name="middle_initial" class="w3-input" maxlength="1">

        <label for="department">Department:</label>
        <select name="department" required class="w3-select">
            <option value="">Select Department</option>
            <option value="Computer Science">CICTS</option>
            <option value="Engineering">CAS</option>
            <option value="Business">CBA</option>
            <option value="Arts">CCJ</option>
            <option value="Engineering">ENGINEERING</option>
            <!-- Add more departments as needed -->
        </select>

        <label for="program">Program:</label>
        <select name="program" required class="w3-select">
            <option value="">Select Program</option>
            <option value="BS Computer Science">BS Computer Science</option>
            <option value="BS Information Technology">BS Information Technology</option>
            <option value="BS Business Administration">BS Business Administration</option>
            <option value="BA Fine Arts">ACT</option>
            <option value="BS Criminology">BS Criminology</option>
            <option value="Civil Engineering">Civil Engineering</option>
            <option value="Computer Engineering">Computer Engineering</option>
            <option value="Electrical Engineering">Electrical Engineering</option>
            <!-- Add more programs as needed -->
        </select>

        <label for="year_graduated">Year Graduated:</label>
        <select name="year_graduated" required class="w3-select">
            <option value="">Select Year</option>
            <?php for ($year = date('Y'); $year >= 2000; $year--): ?>
                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
            <?php endfor; ?>
        </select>

        <label for="contact_number">Contact Number:</label>
        <input type="text" name="contact_number" required class="w3-input">

        <label for="personal_email">Personal Email:</label>
        <input type="email" name="personal_email" required class="w3-input">

        <label for="working_status">Working Status:</label>
        <select name="working_status" required class="w3-select">
            <option value="">Select</option>
            <option value="Employed">Employed</option>
            <option value="Unemployed">Unemployed</option>
            <option value="Selfemployed">Selfemployed</option>
            <option value="Still look for a Job">Still look for a Job</option>
            <option value="Never been employed">Never been employed</option>
        </select>

        <button type="submit" name="add" class="w3-button w3-green">Add Alumni</button>
    </form>
</div>

</body>
</html>
