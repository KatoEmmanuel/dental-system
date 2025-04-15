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
    <title>Patient Records</title>
    <link href="styles.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> <!-- Bootstrap for styling -->
    <style>
        .patient-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .patient-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .patient-name {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
        }
        .patient-date {
            font-size: 14px;
            color: #888;
        }
        .image-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .image-grid img {
            width: 200px; /* Consistent width for all images */
            height: 150px; /* Consistent height for all images */
            object-fit: cover; /* Ensures the image fits within the dimensions */
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s ease; /* Smooth hover effect */
        }
        .image-grid img:hover {
            transform: scale(1.05); /* Slight zoom on hover */
        }
        .observation {
            font-style: italic;
            color: #555;
            margin-top: 10px;
        }

        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8); /* Black background with opacity */
        }
        .modal-content {
            margin: 10% auto;
            display: block;
            max-width: 80%;
            max-height: 80%;
        }
        .modal-content img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
    <div class="container mt-5" style="margin-left: 250px;">
        <h1 class="text-center mb-4">Patient Records</h1>
        <div class="d-flex justify-content-between mb-4">
            <form action="displayImage.php" method="GET" class="form-inline">
                <input type="text" name="search" class="form-control mr-2" placeholder="Search by Name" required>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        <?php 
            // Check if a search query is submitted
            if (isset($_GET['search'])) {
                $search = $_GET['search'];

                // Fetch images related to the search query
                $stmt = $conn->prepare("SELECT * FROM image WHERE name LIKE ?");
                $searchTerm = "%" . $search . "%";
                $stmt->bind_param("s", $searchTerm);
                $stmt->execute();
                $result = $stmt->get_result();

                echo '<h2 class="text-center mb-4">Search Results for "' . htmlspecialchars($search) . '"</h2>';
            } else {
                // Default query to fetch all images
                $result = $conn->query("SELECT * FROM image") or die($conn->error);
            }

            while ($row = $result->fetch_assoc()):
                // Decode JSON data for images
                $extraOralImages = json_decode($row['extra_oral_images'], true);
                $modelCastImages = json_decode($row['model_cast_images'], true);

                // Labels for Extra Oral Images
                $extraOralLabels = [
                    "Full-face front with relaxed lips",
                    "Full face frontal with smile",
                    "Left face profile with relaxed lip",
                    "Right face profile with smile + relaxed lip"
                ];

                // Labels for Model Cast Images
                $modelCastLabels = [
                    "Front occlusion",
                    "Lateral molar + canine relationship",
                    "Lateral molar + canine r'ship (Left)",
                    "Mandibular occlusion AP + lateral dentition",
                    "Maxilla occlusion - AP + lateral dentition"
                ];
        ?>
        <div class="patient-card">
            <!-- Patient Header -->
            <div class="patient-header">
                <div class="patient-name"><?php echo htmlspecialchars($row['name']); ?></div>
                <div class="patient-date"><?php echo date("m/d/Y", strtotime($row['uploaded_at'])); ?></div>
            </div>

            <!-- Tabs for Extra Oral and Model Cast Images -->
            <ul class="nav nav-tabs" id="imageTabs-<?php echo $row['id']; ?>" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="extra-oral-tab-<?php echo $row['id']; ?>" data-bs-toggle="tab" data-bs-target="#extra-oral-<?php echo $row['id']; ?>" type="button" role="tab" aria-controls="extra-oral-<?php echo $row['id']; ?>" aria-selected="true">Extra Oral Images</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="model-cast-tab-<?php echo $row['id']; ?>" data-bs-toggle="tab" data-bs-target="#model-cast-<?php echo $row['id']; ?>" type="button" role="tab" aria-controls="model-cast-<?php echo $row['id']; ?>" aria-selected="false">Model Cast Images</button>
                </li>
            </ul>
            <div class="tab-content mt-3" id="imageTabsContent-<?php echo $row['id']; ?>">
                <!-- Extra Oral Images Tab -->
                <div class="tab-pane fade show active" id="extra-oral-<?php echo $row['id']; ?>" role="tabpanel" aria-labelledby="extra-oral-tab-<?php echo $row['id']; ?>">
                    <div class="image-grid">
                        <?php if (!empty($extraOralImages)): ?>
                            <?php foreach ($extraOralImages as $index => $image): ?>
                                <div>
                                    <img src="<?php echo $image; ?>" alt="Extra Oral Image" onclick="openModal('<?php echo $image; ?>')">
                                    <p class="text-center"><?php echo $extraOralLabels[$index] ?? "Extra Oral Image"; ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No Extra Oral Images uploaded.</p>
                        <?php endif; ?>
                    </div>
                    <p class="observation">Analysis/Observation: <?php echo htmlspecialchars($row['extra_oral_observation']); ?></p>
                </div>

                <!-- Model Cast Images Tab -->
                <div class="tab-pane fade" id="model-cast-<?php echo $row['id']; ?>" role="tabpanel" aria-labelledby="model-cast-tab-<?php echo $row['id']; ?>">
                    <div class="image-grid">
                        <?php if (!empty($modelCastImages)): ?>
                            <?php foreach ($modelCastImages as $index => $image): ?>
                                <div>
                                    <img src="<?php echo $image; ?>" alt="Model Cast Image" onclick="openModal('<?php echo $image; ?>')">
                                    <p class="text-center"><?php echo $modelCastLabels[$index] ?? "Model Cast Image"; ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No Model Cast Images uploaded.</p>
                        <?php endif; ?>
                    </div>
                    <p class="observation">Deduction/Observation: <?php echo htmlspecialchars($row['model_cast_observation']); ?></p>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Modal -->
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <img id="modalImage" src="" alt="Zoomed Image">
        </div>
    </div>

    <script>
        // Open the modal and display the selected image
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modal.style.display = 'block';
            modalImage.src = imageSrc;
        }

        // Close the modal
        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>