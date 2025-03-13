<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients Data</title>
    <link href="styles.css"  rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <div class="sidebar">
        <h2>Menu</h2>
        <ul>
            <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
            <li ><a href="addpatient.php"><i class="fas fa-user"></i> <span style="font-size: 25px; color: black;">ADD PATIENT</span></a></li>
            <li><a href="#"><i class="fas fa-briefcase"></i> View Patients</a></li>
            <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
            <li><a href=""><i class="fas fa-cog"></i> LOGOUT</a></li>
        </ul>
    </div>

    <div class="content">
        <h2>Patients Data</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date of Birth</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Occupation</th>
                    <th>Medical History</th>
                    <th>Phone</th>
                    <th>Parafunctional Behavior</th>
                    <th>Chief Complaint 1</th>
                    <th>Chief Complaint 2</th>
                    <th>Chief Complaint 3</th>
                    <th>Chief Complaint 4</th>
                    <th>Pre Treatment</th>
                    <th>During RX</th>
                    <th>Post RX</th>
                    <th>Skeletal Features</th>
                    <th>Vertical Overlap</th>
                    <th>Lower Crowding</th>
                    <th>Upper Crowding</th>
                    <th>Face Profile</th>
                    <th>Dental Features</th>
                    <th>Contact of Incisors</th>
                    <th>Max Mand Relation</th>
                    <th>Etiology</th>
                    <th>Deciduous Dentition</th>
                    <th>Adults</th>
                </tr>
            </thead>
            <tbody>
                <?php
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

                $sql = "SELECT * FROM patients";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["dob"] . "</td>";
                        echo "<td>" . $row["age"] . "</td>";
                        echo "<td>" . $row["gender"] . "</td>";
                        echo "<td>" . $row["occupation"] . "</td>";
                        echo "<td>" . $row["medical_history"] . "</td>";
                        echo "<td>" . $row["phone"] . "</td>";
                        echo "<td>" . $row["parafunctional_behavior"] . "</td>";
                        echo "<td>" . $row["chief_complaint_1"] . "</td>";
                        echo "<td>" . $row["chief_complaint_2"] . "</td>";
                        echo "<td>" . $row["chief_complaint_3"] . "</td>";
                        echo "<td>" . $row["chief_complaint_4"] . "</td>";
                        echo "<td>" . $row["pre_treatment"] . "</td>";
                        echo "<td>" . $row["during_rx"] . "</td>";
                        echo "<td>" . $row["post_rx"] . "</td>";
                        echo "<td>" . $row["skeletal_features"] . "</td>";
                        echo "<td>" . $row["vertical_overlap_class"] . "</td>";
                        echo "<td>" . $row["lower_crowding"] . "</td>";
                        echo "<td>" . $row["upper_crowding"] . "</td>";
                        echo "<td>" . $row["face_profile"] . "</td>";
                        echo "<td>" . $row["dental_features"] . "</td>";
                        echo "<td>" . $row["contact_of_incisors"] . "</td>";
                        echo "<td>" . $row["max_mand_relation"] . "</td>";
                        echo "<td>" . $row["etiology"] . "</td>";
                        echo "<td>" . $row["deciduous_dentition"] . "</td>";
                        echo "<td>" . $row["adults"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='27'>No patients found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>