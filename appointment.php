<?php
include 'includes/config.php'; // Include the database configuration file
session_start(); // Start the session to access session variables

// Redirect to login page if the user is not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<?php

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

// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$editAppointment = null;
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_id'])) {
    $editId = $_POST['edit_id'];

    // Fetch the appointment details from the database
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $result = $stmt->get_result();
    $editAppointment = $result->fetch_assoc();
    $stmt->close();
}

// Process form data if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_id'])) {
        $deleteId = $_POST['delete_id'];

        // Delete the appointment from the database
        $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->bind_param("i", $deleteId);

        if ($stmt->execute()) {
            $successMessage = "Appointment deleted successfully.";
        } else {
            $successMessage = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // Insert new appointment data
        $patientName = $_POST['patient_name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $doctorName = $_POST['doctor_name'] ?? '';
        $appointmentDate = $_POST['appointment_date'] ?? '';
        $appointmentTime = $_POST['appointment_time'] ?? '';
        $status = $_POST['status'] ?? 'Pending';

        $stmt = $conn->prepare("INSERT INTO appointments (patient_name, phone, email, doctor_name, appointment_date, appointment_time, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("sssssss", $patientName, $phone, $email, $doctorName, $appointmentDate, $appointmentTime, $status);

        if ($stmt->execute()) {
            echo "Appointment scheduled successfully";

            // Send email to user and doctor
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.example.com'; // Set the SMTP server to send through
                $mail->SMTPAuth = true;
                $mail->Username = 'your_email@example.com'; // SMTP username
                $mail->Password = 'your_password'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Enable verbose debug output
                $mail->SMTPDebug = 2;
                $mail->Debugoutput = 'html';

                // Recipients
                $mail->setFrom('your_email@example.com', 'Your Name');
                $mail->addAddress($email, $patientName); // Add a recipient
                $mail->addAddress('katoemmanganda@gmail.com', 'Doctor Kato');

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Appointment Confirmation';
                $mail->Body = "Dear $patientName,<br><br>Your appointment with Dr. $doctorName has been scheduled for $appointmentDate at $appointmentTime.<br><br>Thank you.";

                $mail->send();
                echo 'Email has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_appointment'])) {
    $id = $_POST['id'];
    $patientName = $_POST['patient_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $doctorName = $_POST['doctor_name'];
    $appointmentDate = $_POST['appointment_date'];
    $appointmentTime = $_POST['appointment_time'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE appointments SET patient_name = ?, phone = ?, email = ?, doctor_name = ?, appointment_date = ?, appointment_time = ?, status = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $patientName, $phone, $email, $doctorName, $appointmentDate, $appointmentTime, $status, $id);

    if ($stmt->execute()) {
        $successMessage = "Appointment updated successfully.";
    } else {
        $successMessage = "Error: " . $stmt->error;
    }

    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <link href="styles.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    
    <style>
        .custom-modal .modal-content {
            background-color: #f8f9fa; /* Light gray background */
            border-radius: 10px; /* Rounded corners */
        }

        .custom-modal .modal-header {
            background-color: #007bff; /* Blue header */
            color: white;
        }

        .custom-modal .modal-footer {
            background-color: #f1f1f1; /* Light footer */
        }
        .modal-body-scrollable {
            max-height: 400px;
            overflow-y: auto;
        }
        .content{
            margin-right:0px
        }

    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="content">
        <div class="welcome">
            <span>Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?></span> <a href="#done-appointments">View Done Appointment</a>
            <br><a href="#appointments">Add Appointment</a>
        </div>
        <h2>Appointments</h2>
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($successMessage); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <!-- THIS IS WHERE I PLACE MY APPOINTMENTS THAT ARE Pending -->
 <div class="row mt-5">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-header bg-warning text-white">Pending Appointments</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Patient Name</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Doctor Name</th>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php
    // Fetch appointments from the database
    $sql = "SELECT id, patient_name, phone, email, doctor_name, appointment_date, appointment_time FROM appointments WHERE status = 'Pending'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['doctor_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['appointment_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['appointment_time']) . "</td>";
            echo '<td>';
            echo '<form action="appointment.php" method="POST" style="display:inline-block;">';
            echo '<input type="hidden" name="edit_id" value="' . $row['id'] . '">';
            echo '<button type="submit" class="btn btn-warning">Edit</button>';
            echo '</form>';
            echo '<form action="appointment.php" method="POST" style="display:inline-block; margin-left: 5px;">';
            echo '<input type="hidden" name="delete_id" value="' . $row['id'] . '">';
            echo '<button type="submit" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this appointment?\')">';
            echo '<i class="fas fa-trash-alt"></i>'; // Font Awesome trash icon
            echo '</button>';
            echo '</form>';
            echo '</td>';
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7' class='text-center'>No pending appointments</td></tr>";
    }
    ?>
</tbody>
                </table>
            </div>
        </div>
    </div>
</div>
        <div class="container mt-5" id="appointments">
            <!-- Your content for appointments goes here -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-header">Schedule Appointment</div>
                        <div class="card-body">
                            <form action="appointment.php" method="POST">
                                <div class="form-group">
                                    <label for="patient_name">Patient Name</label>
                                    <input type="text" class="form-control" id="patient_name" name="patient_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="doctor_name">Doctor Name</label>
                                    <input type="text" class="form-control" id="doctor_name" name="doctor_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="appointment_date">Appointment Date</label>
                                    <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                                </div>
                                <div class="form-group">
                                    <label for="appointment_time">Appointment Time</label>
                                    <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="Pending">Pending</option>
                                        <option value="Done">Done</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Schedule Appointment</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5" id="done-appointments">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-header bg-warning text-white">Done  Appointments</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Patient Name</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Doctor Name</th>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php
    // Fetch done appointments from the database
    $sql = "SELECT id, patient_name, phone, email, doctor_name, appointment_date, appointment_time FROM appointments WHERE status = 'Done'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['doctor_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['appointment_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['appointment_time']) . "</td>";
            echo '<td>';
            echo '<form action="appointment.php" method="POST" style="display:inline-block;">';
            echo '<input type="hidden" name="edit_id" value="' . $row['id'] . '">';
            echo '<button type="submit" class="btn btn-warning">Edit</button>';
            echo '</form>';
            echo '<form action="appointment.php" method="POST" style="display:inline-block; margin-left: 5px;">';
            echo '<input type="hidden" name="delete_id" value="' . $row['id'] . '">';
            echo '<button type="submit" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this appointment?\')">';
            echo '<i class="fas fa-trash-alt"></i>'; // Font Awesome trash icon
            echo '</button>';
            echo '</form>';
            echo '</td>';
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7' class='text-center'>No done appointments</td></tr>";
    }
    ?>
</tbody>
                </table>
            </div>
        </div>
    </div>
</div>
            
    <!-- Edit Appointment Modal -->
    <div class="modal fade custom-modal <?php echo isset($editAppointment) ? 'show' : ''; ?>" id="editAppointmentModal" tabindex="-1" role="dialog" aria-labelledby="editAppointmentModalLabel" aria-hidden="true" style="<?php echo isset($editAppointment) ? 'display: block;' : 'display: none;'; ?>">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <form action="appointment.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAppointmentModalLabel">Edit Appointment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="window.location.href='appointment.php';">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body modal-body-scrollable">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($editAppointment['id'] ?? ''); ?>">
                        <div class="form-group">
                            <label for="edit_patient_name">Patient Name</label>
                            <input type="text" class="form-control" id="edit_patient_name" name="patient_name" value="<?php echo htmlspecialchars($editAppointment['patient_name'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_phone">Phone Number</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone" value="<?php echo htmlspecialchars($editAppointment['phone'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_email">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" value="<?php echo htmlspecialchars($editAppointment['email'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_doctor_name">Doctor Name</label>
                            <input type="text" class="form-control" id="edit_doctor_name" name="doctor_name" value="<?php echo htmlspecialchars($editAppointment['doctor_name'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_appointment_date">Appointment Date</label>
                            <input type="date" class="form-control" id="edit_appointment_date" name="appointment_date" value="<?php echo htmlspecialchars($editAppointment['appointment_date'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_appointment_time">Appointment Time</label>
                            <input type="time" class="form-control" id="edit_appointment_time" name="appointment_time" value="<?php echo htmlspecialchars($editAppointment['appointment_time'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_status">Status</label>
                            <select class="form-control" id="edit_status" name="status">
                                <option value="Pending" <?php echo (isset($editAppointment['status']) && $editAppointment['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Done" <?php echo (isset($editAppointment['status']) && $editAppointment['status'] == 'Done') ? 'selected' : ''; ?>>Done</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='appointment.php';">Close</button>
                        <button type="submit" class="btn btn-primary" name="update_appointment">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    document.getElementById('edit_id').value = this.getAttribute('data-id');
                    document.getElementById('edit_patient_name').value = this.getAttribute('data-patient-name');
                    document.getElementById('edit_phone').value = this.getAttribute('data-phone');
                    document.getElementById('edit_email').value = this.getAttribute('data-email');
                    document.getElementById('edit_doctor_name').value = this.getAttribute('data-doctor-name');
                    document.getElementById('edit_appointment_date').value = this.getAttribute('data-appointment-date');
                    document.getElementById('edit_appointment_time').value = this.getAttribute('data-appointment-time');
                    document.getElementById('edit_status').value = this.getAttribute('data-status');
                });
            });
        });
    </script>
</body>
</html>