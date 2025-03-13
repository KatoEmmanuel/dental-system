<?php
// Database Configuration
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'dduungu'; // Database name from your request
$charset = 'utf8mb4';

// Create connection
try {
    $pdo = new PDO(
        "mysql:host=$host;charset=$charset",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $pdo->exec("USE `$dbname`");
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Create tables
$pdo->exec("CREATE TABLE IF NOT EXISTS patients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS medical_entries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    entry_type ENUM('extra_oral_image', 'model_cast_image', 'extra_oral_analysis', 'bolton_analysis') NOT NULL,
    image_type VARCHAR(50),
    image_data LONGBLOB,
    mime_type VARCHAR(50),
    analysis_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id)
)");

// Preload sample data if empty
$stmt = $pdo->query("SELECT COUNT(*) AS count FROM patients");
if ($stmt->fetch()['count'] == 0) {
    try {
        $pdo->beginTransaction();
        
        // Sample patients
        $patients = [
            ['Emma Johnson', '2023-01-15'],
            ['Liam Smith', '2023-02-20'],
            ['Olivia Brown', '2023-03-10']
        ];

        // 1x1 transparent PNG
        $placeholder = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');

        foreach ($patients as $patient) {
            // Insert patient
            $stmt = $pdo->prepare("INSERT INTO patients (full_name, created_at) VALUES (?,?)");
            $stmt->execute([$patient[0], $patient[1]]);
            $pid = $pdo->lastInsertId();

            // Extra oral images
            $types = ['full_face_relaxed', 'full_face_smile', 'left_profile_relaxed', 'right_profile_smile'];
            foreach ($types as $type) {
                $stmt = $pdo->prepare("INSERT INTO medical_entries 
                    (patient_id, entry_type, image_type, image_data, mime_type)
                    VALUES (?, 'extra_oral_image', ?, ?, 'image/png')");
                $stmt->execute([$pid, $type, $placeholder]);
            }

            // Model cast images
            $types = ['front_occlusion', 'lateral_molar_right', 'lateral_molar_left', 'mandibular_occlusion', 'maxilla_occlusion'];
            foreach ($types as $type) {
                $stmt = $pdo->prepare("INSERT INTO medical_entries 
                    (patient_id, entry_type, image_type, image_data, mime_type)
                    VALUES (?, 'model_cast_image', ?, ?, 'image/png')");
                $stmt->execute([$pid, $type, $placeholder]);
            }

            // Analyses
            $stmt = $pdo->prepare("INSERT INTO medical_entries 
                (patient_id, entry_type, analysis_text)
                VALUES (?, 'extra_oral_analysis', ?)");
            $stmt->execute([$pid, "Sample analysis for {$patient[0]}:\n- Normal facial symmetry\n- Good occlusal relationship"]);

            $stmt = $pdo->prepare("INSERT INTO medical_entries 
                (patient_id, entry_type, analysis_text)
                VALUES (?, 'bolton_analysis', ?)");
            $stmt->execute([$pid, "Bolton analysis for {$patient[0]}:\nOverall ratio: 91.5%\nAnterior ratio: 78.3%"]);
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error loading sample data: " . $e->getMessage());
    }
}

// Handle Form Submission
$error = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // Validate required fields
        $required = [
            'full_name' => 'Patient name',
            'extra_oral_analysis' => 'Extra oral analysis',
            'bolton_analysis' => 'Bolton analysis'
        ];
        foreach ($required as $field => $name) {
            if (empty($_POST[$field])) {
                throw new Exception("$name is required");
            }
        }

        // Validate images
        $imageTypes = [
            'extra_oral_images' => 4,
            'model_cast_images' => 5
        ];
        foreach ($imageTypes as $field => $count) {
            if (count($_FILES[$field]['name'] ?? []) !== $count) {
                throw new Exception("All $field are required");
            }
        }

        // Insert patient
        $stmt = $pdo->prepare("INSERT INTO patients (full_name) VALUES (?)");
        $stmt->execute([$_POST['full_name']]);
        $pid = $pdo->lastInsertId();

        // Process images
        $processImages = function($files, $type) use ($pdo, $pid) {
            foreach ($files['tmp_name'] as $key => $tmp) {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($tmp);
                if (!in_array($mime, ['image/jpeg', 'image/png'])) {
                    throw new Exception("Invalid file type for $key");
                }
                $data = file_get_contents($tmp);
                $stmt = $pdo->prepare("INSERT INTO medical_entries 
                    (patient_id, entry_type, image_type, image_data, mime_type)
                    VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$pid, $type, $key, $data, $mime]);
            }
        };

        $processImages($_FILES['extra_oral_images'], 'extra_oral_image');
        $processImages($_FILES['model_cast_images'], 'model_cast_image');

        // Insert analyses
        $stmt = $pdo->prepare("INSERT INTO medical_entries 
            (patient_id, entry_type, analysis_text)
            VALUES (?, 'extra_oral_analysis', ?)");
        $stmt->execute([$pid, $_POST['extra_oral_analysis']]);

        $stmt = $pdo->prepare("INSERT INTO medical_entries 
            (patient_id, entry_type, analysis_text)
            VALUES (?, 'bolton_analysis', ?)");
        $stmt->execute([$pid, $_POST['bolton_analysis']]);

        $pdo->commit();
        $success = "Patient record saved successfully! ID: $pid";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Error: " . $e->getMessage();
    }
}

// Get patient data
$patientData = [];
if (!empty($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM patients WHERE patient_id = ?");
        $stmt->execute([$_GET['id']]);
        while ($row = $stmt->fetch()) {
            if (strpos($row['entry_type'], 'image') !== false) {
                $patientData['images'][$row['entry_type']][$row['image_type']] = [
                    'data' => 'data:' . $row['mime_type'] . ';base64,' . base64_encode($row['image_data']),
                    'created' => $row['created_at']
                ];
            } else {
                $patientData['analyses'][$row['entry_type']] = $row['analysis_text'];
            }
        }
    } catch (Exception $e) {
        $error = "Error loading patient data: " . $e->getMessage();
    }
}

// Search patients
$patients = [];
if (!empty($_GET['search'])) {
    $stmt = $pdo->prepare("SELECT * FROM patients WHERE full_name LIKE ? ORDER BY created_at DESC");
    $stmt->execute(["%{$_GET['search']}%"]);
    $patients = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DDUUNGU Dental System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar { position: fixed; width: 250px; height: 100%; background: #f8f9fa; padding: 20px; }
        .main-content { margin-left: 260px; padding: 20px; }
        .preview-image { max-width: 200px; max-height: 200px; }
        .required::after { content: "*"; color: red; margin-left: 3px; }
        .patient-card { transition: transform 0.2s; }
        .patient-card:hover { transform: translateY(-3px); }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>DDUUNGU Dental</h3>
        <div class="nav flex-column">
            <a href="?" class="btn btn-outline-primary mb-2">New Patient</a>
            <a href="?search=" class="btn btn-outline-secondary">Search Patients</a>
        </div>
    </div>

    <div class="main-content">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['search']) || !isset($_GET['id'])): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search patients..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </div>
                    </form>

                    <?php if (!empty($patients)): ?>
                        <div class="row mt-4">
                            <?php foreach ($patients as $patient): ?>
                                <div class="col-md-6 mb-3">
                                    <a href="?id=<?= $patient['id'] ?>" class="card patient-card text-decoration-none">
                                        <div class="card-body">
                                            <h5><?= htmlspecialchars($patient['full_name']) ?></h5>
                                            <small class="text-muted">ID: <?= $patient['id'] ?></small><br>
                                            <small class="text-muted">Registered: <?= $patient['created_at'] ?></small>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!isset($_GET['id'])): ?>
            <!-- Add Patient Form -->
            <form method="POST" enctype="multipart/form-data">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="mb-4">Patient Information</h4>
                        <div class="form-group">
                            <label class="required">Full Name</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="mb-4">Extra Oral Images <small class="required">(All required)</small></h4>
                        <?php $extraOral = [
                            'Full-face front with relaxed lips',
                            'Full face frontal with smile',
                            'Left face profile with relaxed lip',
                            'Right face profile with smile + relaxed lip'
                        ]; ?>
                        <?php foreach ($extraOral as $key => $label): ?>
                            <div class="form-group">
                                <label class="required"><?= ($key+1) . ". $label" ?></label>
                                <input type="file" name="extra_oral_images[]" 
                                       class="form-control-file" accept="image/*" capture="environment" required>
                            </div>
                        <?php endforeach; ?>

                        <div class="form-group">
                            <label class="required">Extra Oral Analysis</label>
                            <textarea name="extra_oral_analysis" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="mb-4">Model Cast Images <small class="required">(All required)</small></h4>
                        <?php $modelCast = [
                            'Front occlusion',
                            'Lateral molar + canine relationship',
                            'Lateral molar + canine rship (Left)',
                            'Mandibular occlusion AP + lateral dentition',
                            'Maxilla occlusion - AP + lateral dentition'
                        ]; ?>
                        <?php foreach ($modelCast as $key => $label): ?>
                            <div class="form-group">
                                <label class="required"><?= ($key+1) . ". $label" ?></label>
                                <input type="file" name="model_cast_images[]" 
                                       class="form-control-file" accept="image/*" capture="environment" required>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="mb-4">Bolton Analysis <small class="required">(Required)</small></h4>
                        <textarea name="bolton_analysis" class="form-control" rows="4" required></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block">Save Patient Record</button>
            </form>
        <?php else: ?>
            <!-- View Patient -->
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Patient Record #<?= htmlspecialchars($_GET['id']) ?></h4>
                    
                    <?php if (!empty($patientData)): ?>
                        <h5>Extra Oral Images</h5>
                        <div class="row">
                            <?php foreach ($patientData['images']['extra_oral_image'] ?? [] as $type => $img): ?>
                                <div class="col-md-3 mb-4">
                                    <div class="card">
                                        <img src="<?= $img['data'] ?>" class="card-img-top preview-image">
                                        <div class="card-body">
                                            <h6><?= ucfirst(str_replace('_', ' ', $type)) ?></h6>
                                            <small class="text-muted"><?= $img['created'] ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <h5>Model Cast Images</h5>
                        <div class="row">
                            <?php foreach ($patientData['images']['model_cast_image'] ?? [] as $type => $img): ?>
                                <div class="col-md-3 mb-4">
                                    <div class="card">
                                        <img src="<?= $img['data'] ?>" class="card-img-top preview-image">
                                        <div class="card-body">
                                            <h6><?= ucfirst(str_replace('_', ' ', $type)) ?></h6>
                                            <small class="text-muted"><?= $img['created'] ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <h5>Analyses</h5>
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6>Extra Oral Analysis</h6>
                                <p class="text-pre"><?= nl2br(htmlspecialchars($patientData['analyses']['extra_oral_analysis'] ?? '')) ?></p>
                                
                                <h6 class="mt-4">Bolton Analysis</h6>
                                <p class="text-pre"><?= nl2br(htmlspecialchars($patientData['analyses']['bolton_analysis'] ?? '')) ?></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">No patient data found</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>