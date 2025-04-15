<?php
include 'includes/config.php'; // Include the database configuration file
session_start(); // Start the session to access session variables

// Redirect to login page if the user is not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPLOAD IMAGE TO DATABASE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> <!-- Bootstrap for styling -->
    <link href="styles.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include 'sidebar.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Upload Patient Images</h1>
        <div class="d-flex justify-content-end mb-4">
            <a href="displayImage.php" class="btn btn-secondary">View Images</a>
        </div>
        <form action="includes/saveImage.php" method="post" enctype="multipart/form-data">
            <!-- Name Field -->
            <div class="form-group mb-4">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter a name for the images" required>
            </div>

            <!-- Upload Image Section -->
            <h3>EXTRA ORAL IMAGES</h3>
            <div class="form-group">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Extra Oral Images</label>
                    <div class="col-sm-10">
                        <div class="mb-3">
                            <h5 class="text-primary mb-2">1. Full-face front with relaxed lips</h5>
                            <input type="file" class="form-control" name="extra_oral_1" accept="image/*" capture="user">
                            <small class="text-muted">Upload or Take Photo</small>
                        </div>
                        <div class="mb-3">
                            <h5 class="text-primary mb-2">2. Full face frontal with smile</h5>
                            <input type="file" class="form-control" name="extra_oral_2" accept="image/*" capture="user">
                            <small class="text-muted">Upload or Take Photo</small>
                        </div>
                        <div class="mb-3">
                            <h5 class="text-primary mb-2">3. Left face profile with relaxed lip</h5>
                            <input type="file" class="form-control" name="extra_oral_3" accept="image/*" capture="user">
                            <small class="text-muted">Upload or Take Photo</small>
                        </div>
                        <div class="mb-3">
                            <h5 class="text-primary mb-2">4. Right face profile with smile + relaxed lip</h5>
                            <input type="file" class="form-control" name="extra_oral_4" accept="image/*" capture="user">
                            <small class="text-muted">Upload or Take Photo</small>
                        </div>
                    </div>
                </div>
                <!-- Analysis/Observation for Extra Oral Images -->
                <div class="row mb-4">
                    <label class="col-sm-2 col-form-label">Analysis/Observation</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="extra_oral_observation" rows="3" placeholder="Enter analysis and observations for extra oral images"></textarea>
                    </div>
                </div>
            </div>

            <h3>MODEL CAST IMAGES</h3>
            <div class="form-group">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Model Cast Images</label>
                    <div class="col-sm-10">
                        <div class="mb-3">
                            <h5 class="text-primary mb-2">1. Front occlusion</h5>
                            <input type="file" class="form-control" name="model_cast_1" accept="image/*" capture="environment">
                            <small class="text-muted">Upload or Take Photo</small>
                        </div>
                        <div class="mb-3">
                            <h5 class="text-primary mb-2">2. Lateral molar + canine relationship</h5>
                            <input type="file" class="form-control" name="model_cast_2" accept="image/*" capture="environment">
                            <small class="text-muted">Upload or Take Photo</small>
                        </div>
                        <div class="mb-3">
                            <h5 class="text-primary mb-2">3. Lateral molar + canine r'ship (Left)</h5>
                            <input type="file" class="form-control" name="model_cast_3" accept="image/*" capture="environment">
                            <small class="text-muted">Upload or Take Photo</small>
                        </div>
                        <div class="mb-3">
                            <h5 class="text-primary mb-2">4. Mandibular occlusion AP + lateral dentition</h5>
                            <input type="file" class="form-control" name="model_cast_4" accept="image/*" capture="environment">
                            <small class="text-muted">Upload or Take Photo</small>
                        </div>
                        <div class="mb-3">
                            <h5 class="text-primary mb-2">5. Maxilla occlusion - AP + lateral dentition</h5>
                            <input type="file" class="form-control" name="model_cast_5" accept="image/*" capture="environment">
                            <small class="text-muted">Upload or Take Photo</small>
                        </div>
                    </div>
                </div>
                <!-- Analysis/Observation for Model Cast Images -->
                <div class="row mb-4">
                    <label class="col-sm-2 col-form-label">Deduction/Observation</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="model_cast_observation" rows="3" placeholder="Enter deduction and observations for model cast images"></textarea>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>