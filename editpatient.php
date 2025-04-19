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
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the patient data
    $sql = "SELECT * FROM patients WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();
    } else {
        echo "No patient found with ID: $id";
        exit;
    }
} else {
    echo "No ID provided";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $age = $_POST['age'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $occupation = $_POST['occupation'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $medical_history = $_POST['medical_history'] ?? '';
    $parafunctional_behavior = $_POST['parafunctional_behavior'] ?? '';
    $chief_complaint_1 = $_POST['chief_complaint_1'] ?? '';
    $chief_complaint_2 = $_POST['chief_complaint_2'] ?? '';
    $chief_complaint_3 = $_POST['chief_complaint_3'] ?? '';
    $chief_complaint_4 = $_POST['chief_complaint_4'] ?? '';
    $pre_treatment = $_POST['pre_treatment'] ?? '';
    $during_rx = $_POST['during_rx'] ?? '';
    $post_rx = $_POST['post_rx'] ?? '';
    $skeletal_features = isset($_POST['skeletal_features']) ? implode(',', $_POST['skeletal_features']) : '';
    $vertical_overlap_class = $_POST['vertical_overlap_class'] ?? '';
    $vertical_overlap_checkboxes = isset($_POST['vertical_overlap_checkboxes']) ? implode(',', $_POST['vertical_overlap_checkboxes']) : '';
    $lower_crowding = isset($_POST['lower_crowding']) ? implode(',', $_POST['lower_crowding']) : '';
    $upper_crowding = isset($_POST['upper_crowding']) ? implode(',', $_POST['upper_crowding']) : '';
    $face_profile = isset($_POST['face_profile']) ? implode(',', $_POST['face_profile']) : '';
    $dental_features = isset($_POST['dental_features']) ? implode(',', $_POST['dental_features']) : '';
    $contact_of_incisors = $_POST['contact_of_incisors'] ?? '';
    $max_mand_relation = $_POST['max_mand_relation'] ?? '';
    $etiology = isset($_POST['etiology']) ? implode(',', $_POST['etiology']) : '';
    $deciduous_dentition = isset($_POST['deciduous_dentition']) ? implode(',', $_POST['deciduous_dentition']) : '';
    $adults = isset($_POST['adults']) ? implode(',', $_POST['adults']) : '';

    if (empty($name) || empty($dob)) {
        echo "<script>alert('Name and Date of Birth are required fields.');</script>";
    } else {
        // Update query
        $sql = "UPDATE patients SET 
                name='$name', dob='$dob', age='$age', gender='$gender', occupation='$occupation', phone='$phone', 
                medical_history='$medical_history', parafunctional_behavior='$parafunctional_behavior', 
                chief_complaint_1='$chief_complaint_1', chief_complaint_2='$chief_complaint_2', 
                chief_complaint_3='$chief_complaint_3', chief_complaint_4='$chief_complaint_4', 
                pre_treatment='$pre_treatment', during_rx='$during_rx', post_rx='$post_rx', 
                skeletal_features='$skeletal_features', vertical_overlap_class='$vertical_overlap_class', 
                vertical_overlap_checkboxes='$vertical_overlap_checkboxes', lower_crowding='$lower_crowding', 
                upper_crowding='$upper_crowding', face_profile='$face_profile', dental_features='$dental_features', 
                contact_of_incisors='$contact_of_incisors', max_mand_relation='$max_mand_relation', 
                etiology='$etiology', deciduous_dentition='$deciduous_dentition', adults='$adults' 
                WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Record updated successfully'); window.location.href='viewpatient.php';</script>";
        } else {
            echo "<script>alert('Error updating record: " . $conn->error . "');</script>";
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient</title>
    <link href="styles.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Edit Patient</h2>
        <form action="editpatient.php?id=<?php echo $id; ?>" method="POST">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $patient['name']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $patient['dob']; ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="age" class="form-label">Age</label>
                    <input type="text" class="form-control" id="age" name="age" value="<?php echo $patient['age']; ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="male" <?php if ($patient['gender'] == 'male') echo 'selected'; ?>>Male</option>
                        <option value="female" <?php if ($patient['gender'] == 'female') echo 'selected'; ?>>Female</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="occupation" class="form-label">Occupation</label>
                    <input type="text" class="form-control" id="occupation" name="occupation" value="<?php echo $patient['occupation']; ?>">
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $patient['phone']; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="medical_history" class="form-label">Medical & Dental History</label>
                    <textarea class="form-control" id="medical_history" name="medical_history" rows="3"><?php echo $patient['medical_history']; ?></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="parafunctional_behavior" class="form-label">Parafunctional Behavior/Habits</label>
                    <input type="text" class="form-control" id="parafunctional_behavior" name="parafunctional_behavior" value="<?php echo $patient['parafunctional_behavior']; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="chief_complaint_1" class="form-label">Chief Complaint 1</label>
                    <input type="text" class="form-control" id="chief_complaint_1" name="chief_complaint_1" value="<?php echo $patient['chief_complaint_1']; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="chief_complaint_2" class="form-label">Chief Complaint 2</label>
                    <input type="text" class="form-control" id="chief_complaint_2" name="chief_complaint_2" value="<?php echo $patient['chief_complaint_2']; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="chief_complaint_3" class="form-label">Chief Complaint 3</label>
                    <input type="text" class="form-control" id="chief_complaint_3" name="chief_complaint_3" value="<?php echo $patient['chief_complaint_3']; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="chief_complaint_4" class="form-label">Chief Complaint 4</label>
                    <input type="text" class="form-control" id="chief_complaint_4" name="chief_complaint_4" value="<?php echo $patient['chief_complaint_4']; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="pre_treatment" class="form-label">Pre Treatment</label>
                    <textarea class="form-control" id="pre_treatment" name="pre_treatment" rows="3"><?php echo $patient['pre_treatment']; ?></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="during_rx" class="form-label">During RX</label>
                    <textarea class="form-control" id="during_rx" name="during_rx" rows="3"><?php echo $patient['during_rx']; ?></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="post_rx" class="form-label">Post RX</label>
                    <textarea class="form-control" id="post_rx" name="post_rx" rows="3"><?php echo $patient['post_rx']; ?></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="skeletal_features" class="form-label">Skeletal Features</label>
                    <select class="form-select" id="skeletal_features" name="skeletal_features[]" multiple size="4">
                        <option value="increased-lower" <?php if (strpos($patient['skeletal_features'], 'increased-lower') !== false) echo 'selected'; ?>>Increased lower anterior facial height</option>
                        <option value="decreased-upper" <?php if (strpos($patient['skeletal_features'], 'decreased-upper') !== false) echo 'selected'; ?>>Decreased upper ant facial height</option>
                        <option value="short-upper" <?php if (strpos($patient['skeletal_features'], 'short-upper') !== false) echo 'selected'; ?>>Short upper lip</option>
                        <option value="long-narrow" <?php if (strpos($patient['skeletal_features'], 'long-narrow') !== false) echo 'selected'; ?>>Long & Narrow face</option>
                    </select>
                    <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple options</small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="vertical_overlap_class" class="form-label">Number of Vertical Overlap of Teeth</label>
                    <select class="form-select" id="vertical_overlap_class" name="vertical_overlap_class">
                        <option value="class1" <?php if ($patient['vertical_overlap_class'] == 'class1') echo 'selected'; ?>>Class 1</option>
                        <option value="class2-dev1" <?php if ($patient['vertical_overlap_class'] == 'class2-dev1') echo 'selected'; ?>>Class 2 Dev i</option>
                        <option value="class2-dev2" <?php if ($patient['vertical_overlap_class'] == 'class2-dev2') echo 'selected'; ?>>Class 2 Dev ii</option>
                        <option value="class3" <?php if ($patient['vertical_overlap_class'] == 'class3') echo 'selected'; ?>>Class 3 Malochore</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Vertical Overlap</label>
                    <div class="checkbox-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="vertical_overlap_checkboxes[]" value="no" id="vertical-no" <?php if (strpos($patient['vertical_overlap_checkboxes'], 'no') !== false) echo 'checked'; ?>>
                            <label class="form-check-label" for="vertical-no">No</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="vertical_overlap_checkboxes[]" value="yes" id="vertical-yes" <?php if (strpos($patient['vertical_overlap_checkboxes'], 'yes') !== false) echo 'checked'; ?>>
                            <label class="form-check-label" for="vertical-yes">Yes</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Crowding</label>
                    <div class="checkbox-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="lower_crowding[]" value="anterior" id="lower-anterior" <?php if (strpos($patient['lower_crowding'], 'anterior') !== false) echo 'checked'; ?>>
                            <label class="form-check-label" for="lower-anterior">Lower Anterior</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="lower_crowding[]" value="posterior" id="lower-posterior" <?php if (strpos($patient['lower_crowding'], 'posterior') !== false) echo 'checked'; ?>>
                            <label class="form-check-label" for="lower-posterior">Lower Posterior</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="upper_crowding[]" value="anterior" id="upper-anterior" <?php if (strpos($patient['upper_crowding'], 'anterior') !== false) echo 'checked'; ?>>
                            <label class="form-check-label" for="upper-anterior">Upper Anterior</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="upper_crowding[]" value="posterior" id="upper-posterior" <?php if (strpos($patient['upper_crowding'], 'posterior') !== false) echo 'checked'; ?>>
                            <label class="form-check-label" for="upper-posterior">Upper Posterior</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Face Profile</label>
                    <div class="checkbox-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="face_profile[]" value="convex" id="convex" <?php if (strpos($patient['face_profile'], 'convex') !== false) echo 'checked'; ?>>
                            <label class="form-check-label" for="convex">Convex</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="face_profile[]" value="concave" id="concave" <?php if (strpos($patient['face_profile'], 'concave') !== false) echo 'checked'; ?>>
                            <label class="form-check-label" for="concave">Concave</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="face_profile[]" value="straight" id="straight" <?php if (strpos($patient['face_profile'], 'straight') !== false) echo 'checked'; ?>>
                            <label class="form-check-label" for="straight">Straight</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Dental Features</label>
                    <div class="checkbox-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dental_features[]" value="normal" id="normal" <?php if (strpos($patient['dental_features'], 'normal') !== false) echo 'checked'; ?>>
                            <label class="form-check-label" for="normal">Normal</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dental_features[]" value="proclined" id="proclined" <?php if (strpos($patient['dental_features'], 'proclined') !== false) echo 'checked'; ?>>
                            <label class="form-check-label" for="proclined">Proclined</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dental_features[]" value="incisors" id="incisors" <?php if (strpos($patient['dental_features'], 'incisors') !== false) echo 'checked'; ?>>
                            <label class="form-check-label" for="incisors">Incisors</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="contact_of_incisors" class="form-label">Contact of Incisors</label>
                    <input type="text" class="form-control" id="contact_of_incisors" name="contact_of_incisors" value="<?php echo $patient['contact_of_incisors']; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="max_mand_relation" class="form-label">Max-Mand Relation</label>
                    <input type="text" class="form-control" id="max_mand_relation" name="max_mand_relation" value="<?php echo $patient['max_mand_relation']; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="etiology" class="form-label">Etiology</label>
                    <select class="form-select" id="etiology" name="etiology[]" multiple size="4">
                        <option value="genetic" <?php if (strpos($patient['etiology'], 'genetic') !== false) echo 'selected'; ?>>Genetic</option>
                        <option value="environmental" <?php if (strpos($patient['etiology'], 'environmental') !== false) echo 'selected'; ?>>Environmental</option>
                        <option value="habit" <?php if (strpos($patient['etiology'], 'habit') !== false) echo 'selected'; ?>>Habit</option>
                        <option value="trauma" <?php if (strpos($patient['etiology'], 'trauma') !== false) echo 'selected'; ?>>Trauma</option>
                    </select>
                    <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple options</small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="deciduous_dentition" class="form-label">Deciduous Dentition</label>
                    <select class="form-select" id="deciduous_dentition" name="deciduous_dentition[]" multiple size="4">
                        <option value="normal" <?php if (strpos($patient['deciduous_dentition'], 'normal') !== false) echo 'selected'; ?>>Normal</option>
                        <option value="abnormal" <?php if (strpos($patient['deciduous_dentition'], 'abnormal') !== false) echo 'selected'; ?>>Abnormal</option>
                    </select>
                    <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple options</small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="adults" class="form-label">Adults</label>
                    <select class="form-select" id="adults" name="adults[]" multiple size="4">
                        <option value="normal" <?php if (strpos($patient['adults'], 'normal') !== false) echo 'selected'; ?>>Normal</option>
                        <option value="abnormal" <?php if (strpos($patient['adults'], 'abnormal') !== false) echo 'selected'; ?>>Abnormal</option>
                    </select>
                    <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple options</small>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Patient</button>
        </form>
    </div>
</body>
</html>