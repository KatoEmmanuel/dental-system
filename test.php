<?php
include 'includes/config.php';
?>
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
                        // Fetch pending appointments from the database
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