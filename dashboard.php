<?php
session_start();
include 'connect.php';

// Establish the database connection
$conn = connect(); 

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch alumni data with LEFT JOIN to get working status
$query = "
    SELECT a.al_id_no, a.first_name, a.last_name, a.middle_initial, a.department, 
           a.program, a.year_graduated, a.contact_number, a.personal_email, 
           ws.Employment AS Employment
    FROM `2024-2025` a 
    LEFT JOIN `2024-2025ed` ws ON a.al_id_no = ws.al_id_no
";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Handle deletion of alumni record
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete_query = "DELETE FROM `2024-2025` WHERE al_id_no = '$delete_id'";

    if (mysqli_query($conn, $delete_query)) {
        header("Location: dashboard.php");
        exit();
    } else {
        die("Delete failed: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Dashboard</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
        }
        .header a {
            margin: 0 10px;
            color: white;
            text-decoration: none;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
        }
        .w3-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }
        .w3-table th, .w3-table td {
            padding: 8px;
            text-align: left;
        }
        .w3-table th {
            background-color: #f2f2f2;
        }
        .actions a {
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Alumni Dashboard</h1>
    <div>
        <a href="add_alumni.php" class="w3-button w3-green">Add Alumni</a>
        <a href="logout.php" class="w3-button w3-red w3-round-large">Logout</a>
    </div>
</div>

<div class="container">
    <h2>Alumni List</h2>

    <table class="w3-table w3-bordered">
        <thead>
            <tr>
                <th>Alumni ID</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Initial</th>
                <th>Department</th>
                <th>Program</th>
                <th>Year Graduated</th>
                <th>Contact Number</th>
                <th>Personal Email</th>
                <th>Working Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['al_id_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['middle_initial']); ?></td>
                    <td><?php echo htmlspecialchars($row['department']); ?></td>
                    <td><?php echo htmlspecialchars($row['program']); ?></td>
                    <td><?php echo htmlspecialchars($row['year_graduated']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['personal_email']); ?></td>
                    <td><?php echo htmlspecialchars($row['Employment'] ?? 'Not Specified'); ?></td>
                    <td class="actions">
                        <a href="edit_alumni.php?id=<?php echo urlencode($row['al_id_no']); ?>" class="w3-button w3-blue">Edit</a>
                        <a href="?delete_id=<?php echo urlencode($row['al_id_no']); ?>" class="w3-button w3-red" onclick="return confirm('Are you sure you want to delete this alumni?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
