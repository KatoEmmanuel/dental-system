<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $occupation = $_POST['occupation'];
    $phone = $_POST['phone'];
    $medical_history = $_POST['medical_history'];
    $parafunctional_behavior = $_POST['parafunctional_behavior'];
    $chief_complaint_1 = $_POST['chief_complaint_1'];
    $chief_complaint_2 = $_POST['chief_complaint_2'];
    $chief_complaint_3 = $_POST['chief_complaint_3'];
    $chief_complaint_4 = $_POST['chief_complaint_4'];
    $pre_treatment = $_POST['pre_treatment'];
    $during_rx = $_POST['during_rx'];
    $post_rx = $_POST['post_rx'];
    $skeletal_features = implode(',', $_POST['skeletal_features']);
    $vertical_overlap_class = $_POST['vertical_overlap_class'];
    $vertical_overlap_checkboxes = implode(',', $_POST['vertical_overlap_checkboxes']);
    $lower_crowding = implode(',', $_POST['lower_crowding']);
    $upper_crowding = implode(',', $_POST['upper_crowding']);
    $face_profile = implode(',', $_POST['face_profile']);
    $dental_features = implode(',', $_POST['dental_features']);
    $contact_of_incisors = implode(',', $_POST['contact_of_incisors']);
    $max_mand_relation = $_POST['max_mand_relation'];
    $etiology = implode(',', $_POST['etiology']);
    $deciduous_dentition = implode(',', $_POST['deciduous_dentition']);
    $adults = implode(',', $_POST['adults']);

    $sql = "INSERT INTO patients (name, dob, age, gender, occupation, phone, medical_history, parafunctional_behavior, chief_complaint_1, chief_complaint_2, chief_complaint_3, chief_complaint_4, pre_treatment, during_rx, post_rx, skeletal_features, vertical_overlap_class, vertical_overlap_checkboxes, lower_crowding, upper_crowding, face_profile, dental_features, contact_of_incisors, max_mand_relation, etiology, deciduous_dentition, adults)
    VALUES ('$name', '$dob', '$age', '$gender', '$occupation', '$phone', '$medical_history', '$parafunctional_behavior', '$chief_complaint_1', '$chief_complaint_2', '$chief_complaint_3', '$chief_complaint_4', '$pre_treatment', '$during_rx', '$post_rx', '$skeletal_features', '$vertical_overlap_class', '$vertical_overlap_checkboxes', '$lower_crowding', '$upper_crowding', '$face_profile', '$dental_features', '$contact_of_incisors', '$max_mand_relation', '$etiology', '$deciduous_dentition', '$adults')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients Data</title>
    <link href="styles.css"  rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin: 50px auto;
            max-width: 1200px;
        }
        .form-container h2 {
            color: #007bff;
            margin-bottom: 30px;
            font-weight: 700;
            text-align: center;
        }
        .form-group label {
            font-weight: 500;
            color: #495057;
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 10px;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        .btn-submit {
            background: #007bff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            font-weight: 500;
            transition: background 0.3s ease;
            width: 100%;
        }
        .btn-submit:hover {
            background: #0056b3;
        }
        .section-title {
            color: #007bff;
            margin-top: 30px;
            margin-bottom: 20px;
            font-weight: 600;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .file-upload {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border: 1px dashed #ced4da;
        }
        .file-upload small {
            color: #6c757d;
        }
        .checkbox-group {
            margin-bottom: 15px;
        }
        .checkbox-group .form-check {
            margin-right: 15px;
        }
    </style>

</head>
<body>
    <div class="sidebar">
        <h2>Menu</h2>
        <ul>
            <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
            <li ><a href="addpatient.php"><i class="fas fa-user"></i> <span style="font-size: 25px; color: black;">ADD PATIENT</span></a></li>
            <li><a href="#"><i class="fas fa-briefcase"></i> View Patients</a></li>
            <li><a href=""><i class="fas fa-envelope"></i> Contact</a></li>
            <li><a href=""><i class="fas fa-cog"></i> LOGOUT</a></li>
        </ul>
    </div>
    <div class="container" style="margin-left: 250px;">
        <div class="form-container">
            <h2>Patient Registration Form</h2>

            <form action="addpatient.php" method="POST" enctype="multipart/form-data">
                <!-- Personal Information -->
                <h4 class="section-title">Personal Information</h4>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Patient Name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="age" class="form-label">Age</label>
                        <input type="text" class="form-control" id="age" name="age" readonly placeholder="Age will be calculated automatically">
                    </div>
                    <div class="col-md-6">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="occupation" class="form-label">Occupation</label>
                        <input type="text" class="form-control" id="occupation" name="occupation" placeholder="Patient Occupation">
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Patient Phone">
                    </div>
                </div>

                <!-- Medical Information -->
                <h4 class="section-title">Medical Information</h4>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="medical_history" class="form-label">Medical & Dental History</label>
                        <textarea class="form-control" id="medical_history" name="medical_history" rows="3" placeholder="Patient Medical & Dental History"></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="parafunctional_behavior" class="form-label">Parafunctional Behavior/Habits</label>
                        <input type="text" class="form-control" id="parafunctional_behavior" name="parafunctional_behavior" placeholder="Patient Parafunctional behavior/Habits">
                    </div>
                </div>

                <!-- Chief Complaints -->
                <h4 class="section-title">Chief Complaints</h4>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <input type="text" class="form-control mb-2" name="chief_complaint_1" placeholder="Chief Complaint 1">
                        <input type="text" class="form-control mb-2" name="chief_complaint_2" placeholder="Chief Complaint 2">
                        <input type="text" class="form-control mb-2" name="chief_complaint_3" placeholder="Chief Complaint 3">
                        <input type="text" class="form-control" name="chief_complaint_4" placeholder="Chief Complaint 4">
                    </div>
                </div>

                <!-- Status of the Case -->
                <h4 class="section-title">Status of the Case</h4>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="pre_treatment" class="form-label">Pre Treatment</label>
                        <textarea class="form-control" id="pre_treatment" name="pre_treatment" rows="3" placeholder="Diagnosis, Rx objective, Rx alternatives"></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="during_rx" class="form-label">During Rx</label>
                        <textarea class="form-control" id="during_rx" name="during_rx" rows="3" placeholder="Assessment of Rx progress"></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="post_rx" class="form-label">Post Rx</label>
                        <textarea class="form-control" id="post_rx" name="post_rx" rows="3" placeholder="Assessment of Rx results and retention management"></textarea>
                    </div>
                </div>


                <!-- Clinical Features -->
                <h4 class="section-title">Clinical Features</h4>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="skeletal_features" class="form-label">Skeletal Features</label>
                        <select class="form-select" id="skeletal_features" name="skeletal_features[]" multiple size="4">
                            <option value="increased-lower">Increased lower anterior facial height</option>
                            <option value="decreased-upper">Decreased upper ant facial height</option>
                            <option value="short-upper">Short upper lip</option>
                            <option value="long-narrow">Long & Narrow face</option>
                        </select>
                        <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple options</small>
                    </div>
                </div>

               <!-- Updated Vertical Overlap Section -->
               <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="vertical_overlap_class" class="form-label">Number of Vertical Overlap of Teeth</label>
                        <select class="form-select" id="vertical_overlap_class" name="vertical_overlap_class">
                            <option value="">Select Vertical Overlap</option>
                            <option value="class1">Class 1</option>
                            <option value="class2-dev1">Class 2 Dev i</option>
                            <option value="class2-dev2">Class 2 Dev ii</option>
                            <option value="class3">Class 3 Malochore</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Vertical Overlap</label>
                        <div class="checkbox-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="vertical_overlap_checkboxes[]" value="no" id="vertical-no">
                                <label class="form-check-label" for="vertical-no">No</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="vertical_overlap_checkboxes[]" value="yes" id="vertical-yes">
                                <label class="form-check-label" for="vertical-yes">Yes</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Crowding -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Crowding</label>
                        <div class="checkbox-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="lower_crowding[]" value="anterior" id="lower-anterior">
                                <label class="form-check-label" for="lower-anterior">Lower Anterior</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="lower_crowding[]" value="posterior" id="lower-posterior">
                                <label class="form-check-label" for="lower-posterior">Lower Posterior</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="upper_crowding[]" value="anterior" id="upper-anterior">
                                <label class="form-check-label" for="upper-anterior">Upper Anterior</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="upper_crowding[]" value="posterior" id="upper-posterior">
                                <label class="form-check-label" for="upper-posterior">Upper Posterior</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Face Profile -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Face Profile</label>
                        <div class="checkbox-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="face_profile[]" value="convex" id="convex">
                                <label class="form-check-label" for="convex">Convex</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="face_profile[]" value="concave" id="concave">
                                <label class="form-check-label" for="concave">Concave</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="face_profile[]" value="straight" id="straight">
                                <label class="form-check-label" for="straight">Straight</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dental Features -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Dental Features</label>
                        <div class="checkbox-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dental_features[]" value="normal" id="normal">
                                <label class="form-check-label" for="normal">Normal</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dental_features[]" value="proclined" id="proclined">
                                <label class="form-check-label" for="proclined">Proclined</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dental_features[]" value="incisors" id="incisors">
                                <label class="form-check-label" for="incisors">Incisors</label>
                            </div>
                        </div>
                    </div>
                </div>

               

                <!-- Contact of Incisors -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Contact of Incisors</label>
                        <div class="checkbox-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="contact_of_incisors[]" value="no" id="contact-no">
                                <label class="form-check-label" for="contact-no">No</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="contact_of_incisors[]" value="yes" id="contact-yes">
                                <label class="form-check-label" for="contact-yes">Yes</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Max/Mand Relation -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="max_mand_relation" class="form-label">Max/Mand Relation</label>
                        <input type="text" class="form-control" id="max_mand_relation" name="max_mand_relation" placeholder="Describe the Max/Mand relation">
                    </div>
                </div>

                <!-- Etiology -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Etiology</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="etiology[]" value="thumb" id="thumb">
                                    <label class="form-check-label" for="thumb">Thumb Sucking</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="etiology[]" value="tongue" id="tongue">
                                    <label class="form-check-label" for="tongue">Tongue Thrust</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="etiology[]" value="airway" id="airway">
                                    <label class="form-check-label" for="airway">Airway obstruction</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="etiology[]" value="growth" id="growth">
                                    <label class="form-check-label" for="growth">Growth abnormality</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="etiology[]" value="cleft" id="cleft">
                                    <label class="form-check-label" for="cleft">Cleft lip/palate</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="etiology[]" value="mouth" id="mouth">
                                    <label class="form-check-label" for="mouth">Mouth breathing</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Section -->
                <h4 class="section-title">Management</h4>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="deciduous_dentition" class="form-label">Deciduous Dentition</label>
                        <select class="form-select" id="deciduous_dentition" name="deciduous_dentition[]" multiple size="5">
                            <option value="habit-breakers">Habit Breakers</option>
                            <option value="headgear">Headgear</option>
                            <option value="functional-appliance">Functional Appliance</option>
                            <option value="chin-cup-therapy">Chin Cup Therapy</option>
                        </select>
                        <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple options</small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="adults" class="form-label">Adults</label>
                        <select class="form-select" id="adults" name="adults[]" multiple size="10">
                            <option value="fixed-braces">Fixed Braces MBT/Roth</option>
                            <option value="elastics">Elastics</option>
                            <option value="transpalatal-arch">Transpalatal Arch</option>
                            <option value="reverse-headgear">Reverse Full Headgear</option>
                            <option value="herbsts-appliance">Herbsts Appliance</option>
                            <option value="lip-bumper">Lip Bumper</option>
                            <option value="torque-springs">Torque Springs</option>
                            <option value="bio-bite">Bio-bite</option>
                            <option value="arch-springs">Arch Springs</option>
                            <option value="rotation-wedge">Rotation Wedge</option>
                            <option value="carrier-motion">Carrier Motion Distalizer</option>
                            <option value="tad">TAD/Microscrew Anchorage</option>
                            <option value="surgery">Surgery</option>
                            <option value="retention-method">Retention Method (Removable/Fixed)</option>
                        </select>
                        <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple options</small>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn-submit">Submit Patient Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <!-- Custom JS for Age Calculation -->
    
    <script>
        // Updated age calculation
        document.getElementById('dob').addEventListener('change', function () {
            const dob = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;

            }

            document.getElementById('age').value = age;
        });
    </script>
</body>
</html>


