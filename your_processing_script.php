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

// Function to handle file upload and compress image if it exceeds 64KB
function processImage($fileInput) {
    if (isset($_FILES[$fileInput]) && $_FILES[$fileInput]['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES[$fileInput];
        $fileSize = $file['size'];
        $fileTmpName = $file['tmp_name'];

        // Check if the file size exceeds 64KB
        if ($fileSize > 65536) {
            // Compress the image
            $image = imagecreatefromstring(file_get_contents($fileTmpName));
            ob_start();
            imagejpeg($image, null, 75); // Adjust the quality parameter as needed
            $compressedImage = ob_get_clean();
            imagedestroy($image);
            return $compressedImage;
        } else {
            // Return the original image content
            return file_get_contents($fileTmpName);
        }
    }
    return null;
}

try {
    // Process form data
    $patientName = $_POST['patient_name'] ?? '';
    $extraOralAnalysis = $_POST['extra_oral_analysis'] ?? '';
    $modelCastAnalysis = $_POST['model_cast_analysis'] ?? '';

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO patient_images (
        patient_name,
        extra_oral1, extra_oral2, extra_oral3, extra_oral4,
        model_cast1, model_cast2, model_cast3, model_cast4, model_cast5,
        extra_oral_analysis, model_cast_analysis
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Process and bind images
    $null = null;
    $stmt->bind_param("sbbbbbbbbsss", 
        $patientName,
        $null, $null, $null, $null,
        $null, $null, $null, $null, $null,
        $extraOralAnalysis, $modelCastAnalysis
    );

    // Bind images to parameters
    $images = [
        processImage('extra_oral_1'),
        processImage('extra_oral_2'),
        processImage('extra_oral_3'),
        processImage('extra_oral_4'),
        processImage('model_cast_1'),
        processImage('model_cast_2'),
        processImage('model_cast_3'),
        processImage('model_cast_4'),
        processImage('model_cast_5')
    ];

    foreach ($images as $index => $image) {
        if ($image !== null) {
            $stmt->send_long_data($index + 1, $image);
        }
    }

    // Execute query
    if ($stmt->execute()) {
        echo "Record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
} finally {
    $conn->close();
}
?>