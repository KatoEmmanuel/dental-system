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

// Initialize search query
$search_query = "";

// Check if the search form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_query = $_POST['search_query'] ?? '';
}

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
    <style>
        .modal-body p {
            margin-bottom: 0.5rem;
        }
        .modal-body p strong {
            display: inline-block;
            width: 150px;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="content">
        <div class="welcome">
            <span>Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?></span>
        </div>
        <h2>Patients Data</h2>
        
        <!-- Search Form -->
        <form id="searchForm" action="viewpatient.php" method="POST" class="form-inline mb-3">
            <input type="text" name="search_query" class="form-control mr-2" placeholder="Search patients" value="<?php echo htmlspecialchars($search_query); ?>" oninput="autoSearch()">
        </form>

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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Modify the SQL query to filter results based on the search input
                if (empty($search_query)) {
                    $sql = "SELECT * FROM patients";
                } else {
                    $sql = "SELECT * FROM patients WHERE name LIKE '%$search_query%' OR phone LIKE '%$search_query%' OR medical_history LIKE '%$search_query%'";
                }
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr onclick='showPatientDetails(" . json_encode($row) . ")'>";
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
                        echo "<td>
                                <div class='btn-group' role='group'>
                                    <a href='editpatient.php?id=" . $row["id"] . "' class='btn btn-primary btn-sm'>Edit</a>
                                    <a href='image2.php?id=" . $row["id"] . "' class='btn btn-secondary btn-sm'>Add Image</a>
                                </div>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='28'>No patients found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="patientModal" tabindex="-1" role="dialog" aria-labelledby="patientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="patientModalLabel">Patient Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Patient details will be populated here -->
                    <div id="patientDetails"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="index.js"></script>
</body>
</html>