<?php
include 'includes/config.php'; // Include the database configuration file
session_start(); // Start the session to access session variables

// Redirect to login page if the user is not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dduungu";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for password change
$password_error = "";
$password_success = "";

// Handle password change form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $username = $_SESSION['username'] ?? 'Admin';

    // Check if new password and confirm password match
    if ($new_password !== $confirm_password) {
        $password_error = "New password and confirm password do not match.";
    } else {
        // Fetch the current password from the database
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        // Verify the current password
        if (!password_verify($current_password, $hashed_password)) {
            $password_error = "Current password is incorrect.";
        } else {
            // Hash the new password
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->bind_param("ss", $new_hashed_password, $username);
            if ($stmt->execute()) {
                $password_success = "Password changed successfully.";
            } else {
                $password_error = "Failed to update the password. Please try again.";
            }
            $stmt->close();
        }
    }
}

// Initialize variables for adding a new user
$new_user_error = "";
$new_user_success = "";

// Handle add new user form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Check if new password and confirm password match
    if ($new_password !== $confirm_new_password) {
        $new_user_error = "New password and confirm password do not match.";
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $new_username, $hashed_password);
        if ($stmt->execute()) {
            $new_user_success = "New user added successfully.";
        } else {
            $new_user_error = "Failed to add the new user. The username might already exist.";
        }
        $stmt->close();
    }
}

// Get the number of patients
$sql = "SELECT COUNT(*) AS patient_count FROM patients";
$result = $conn->query($sql);
$patient_count = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $patient_count = $row['patient_count'];
}

// Get the number of pending appointments
$sql = "SELECT COUNT(*) AS pending_appointments FROM appointments WHERE status = 'Pending'";
$result = $conn->query($sql);
$pending_appointments = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pending_appointments = $row['pending_appointments'];
}

// Get the total number of users
$sql = "SELECT COUNT(*) AS user_count FROM users";
$result = $conn->query($sql);
$user_count = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_count = $row['user_count'];
}

// Fetch appointment data for the pie chart
$sql = "SELECT 
            SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_count,
            SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed_count,
            SUM(CASE WHEN status = 'Canceled' THEN 1 ELSE 0 END) AS canceled_count
        FROM appointments";
$result = $conn->query($sql);

$pending_count = 0;
$completed_count = 0;
$canceled_count = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pending_count = $row['pending_count'];
    $completed_count = $row['completed_count'];
    $canceled_count = $row['canceled_count'];
}

// Fetch upcoming appointments
$sql = "SELECT patient_name, appointment_date, appointment_time FROM appointments WHERE appointment_date >= CURDATE() ORDER BY appointment_date ASC";
$result = $conn->query($sql);
$upcoming_appointments = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $upcoming_appointments[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients Data</title>
    <link href="styles.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="content">
        <div class="welcome">
            <span>
                Welcome, 
                <?php 
                    // Display the username if set, otherwise default to "Admin"
                    echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; 
                ?>
            </span>
            <button class="btn btn-link" data-toggle="modal" data-target="#changePasswordModal">Change Password</button>
            <button class="btn btn-link" data-toggle="modal" data-target="#addUserModal">Add New User</button>
        </div>
        <h2>Admin Dasboard</h2>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-header">Total Patients</div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $patient_count; ?></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-header">Pending Appointments</div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $pending_appointments; ?></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-header">Total Users</div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $user_count; ?></h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">Quick Links</div>
                        <div class="card-body">
                            <ul>
                                <li><a href="addpatient.php">Add New Patient</a></li>
                                <li><a href="viewpatient.php">View All Patients</a></li>
                                <li><a href="appointment.php">Schedule Appointment</a></li>
                                <li><a href="appointment">View All Appointments</a></li>
                                <li><a href="displayimage.php">View All Images</a></li>
                                <!-- Add more quick links here -->
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">Upcoming Appointments</div>
                        <div class="card-body">
                            <?php if (!empty($upcoming_appointments)): ?>
                                <ul class="list-group">
                                    <?php foreach ($upcoming_appointments as $appointment): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>
                                                <strong><?php echo htmlspecialchars($appointment['patient_name']); ?></strong>
                                                <br>
                                                <?php echo htmlspecialchars($appointment['appointment_date']); ?> at <?php echo htmlspecialchars($appointment['appointment_time']); ?>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-muted">No upcoming appointments.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">Quick Links</div>
                        <div class="card-body">
                            <ul>
                                <li><a href="addpatient.php">Add New Patient</a></li>
                                <li><a href="viewpatient.php">View All Patients</a></li>
                                <li><a href="appointment.php">Schedule Appointment</a></li>
                                <li><a href="appointment">View All Appointments</a></li>
                                <li><a href="displayimage.php">View All Images</a></li>
                                <!-- Add more quick links here -->
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">Appointment Status Breakdown</div>
                        <div class="card-body">
                            <canvas id="appointmentPieChart" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Example of a chart (you can use a library like Chart.js) -->
            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-header">Patient Growth</div>
                        <div class="card-body">
                            <canvas id="patientGrowthChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-header">Appointment Status Breakdown</div>
                        <div class="card-body">
                            <canvas id="appointmentPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if ($password_error): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $password_error; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($password_success): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $password_success; ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if ($new_user_error): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $new_user_error; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($new_user_success): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $new_user_success; ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="new_username">Username</label>
                            <input type="text" class="form-control" id="new_username" name="new_username" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password">Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_new_password">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
                        </div>
                        <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('patientGrowthChart').getContext('2d');
        var patientGrowthChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                    label: 'Number of Patients',
                    data: [10, 20, 30, 40, 50, 60, 70],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Appointment Pie Chart
        var ctx2 = document.getElementById('appointmentPieChart').getContext('2d');
        var appointmentPieChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Pending', 'Completed', 'Canceled'],
                datasets: [{
                    data: [<?php echo $pending_count; ?>, <?php echo $completed_count; ?>, <?php echo $canceled_count; ?>],
                    backgroundColor: ['#ffc107', '#28a745', '#dc3545'],
                    borderColor: ['#ffc107', '#28a745', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Check if there are errors or success messages for adding a user
            <?php if (!empty($new_user_error) || !empty($new_user_success)): ?>
                $('#addUserModal').modal('show');
            <?php endif; ?>

            // Check if there are errors or success messages for changing the password
            <?php if (!empty($password_error) || !empty($password_success)): ?>
                $('#changePasswordModal').modal('show');
            <?php endif; ?>
        });
    </script>
</body>
</html>

