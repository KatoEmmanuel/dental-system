<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life Link Hospital</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #007bff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand img {
            max-height: 50px;
            margin-right: 10px;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
        }

        .nav-link {
            color: #fff !important;
            font-weight: 500;
            margin-right: 15px;
        }

        .nav-link:hover {
            color: #ffc107 !important;
        }

        .btn-outline-light {
            font-weight: 600;
            padding: 5px 15px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .btn-outline-light:hover {
            background-color: #ffc107;
            color: #343a40;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 30px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 30px;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        #banner {
            position: relative;
            margin-bottom: 50px;
        }

        #banner .carousel-item img {
            height: 500px;
            object-fit: cover;
        }

        #banner .carousel-caption {
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
        }

        #banner .carousel-caption h5 {
            font-size: 2rem;
            color: #ffc107;
            font-weight: 600;
        }

        #banner .carousel-caption p {
            font-size: 1.2rem;
            color: #fff;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
        }

        .hero-section {
            padding: 60px 20px;
            text-align: center;
            background-color: #007bff;
            color: #fff;
        }

        .hero-section h1 {
            font-size: 2.5rem;
            font-weight: 600;
        }

        .hero-section p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .appointment-section {
            padding: 60px 20px;
        }

        .appointment-section h2 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .footer {
            background-color: #343a40;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }

        .footer p {
            margin: 0;
            font-size: 0.9rem;
        }

        .footer a {
            color: #ffc107;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2rem;
            }

            .hero-section p {
                font-size: 1rem;
            }

            #banner .carousel-item img {
                height: 300px;
            }

            .appointment-section h2 {
                font-size: 1.5rem;
            }

            #banner .carousel-caption h5 {
                font-size: 1.5rem;
            }

            #banner .carousel-caption p {
                font-size: 1rem;
            }

            #banner .carousel-caption {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="images/logo.png" alt="Life Link Dental Logo">
                Life Link Dental
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#appointment">Book Appointment</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light login-btn" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Banner Section -->
    <section id="banner" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/dent1.jpg" class="d-block w-100" alt="Dental Care">
                <div class="carousel-caption">
                    <h5>Comprehensive Dental Care</h5>
                    <p>We provide the best dental services for your family.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="images/detwoman.jpg" class="d-block w-100" alt="Dental Checkup">
                <div class="carousel-caption">
                    <h5>Professional Dentists</h5>
                    <p>Our team is dedicated to your oral health.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="images/smile.jpg" class="d-block w-100" alt="Beautiful Smile">
                <div class="carousel-caption">
                    <h5>Brighten Your Smile</h5>
                    <p>Let us help you achieve the perfect smile.</p>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#banner" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#banner" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </section>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Welcome to Life Link Dental</h1>
            <p>Your health is our priority. Schedule your appointment today.</p>
            <div class="d-flex justify-content-center mt-3">
                <a href="#appointment" class="btn btn-primary btn-lg mr-2">Create Appointment</a>
                <a href="https://wa.me/256702857190?text=Hello%20Life%20Link%20Dental,%20I%20would%20like%20to%20book%20an%20appointment." 
                   target="_blank" 
                   class="btn btn-success btn-lg">
                    <i class="fab fa-whatsapp"></i> Book via WhatsApp
                </a>
            </div>
        </div>
    </section>

    <!-- Appointment Section -->
    <section id="appointment" class="appointment-section">
        <div class="container">
            <h2 class="text-center mb-5">Create an Appointment</h2>
            <form id="appointmentForm" action="home.php" method="POST">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="patient_name">Patient Name</label>
                        <input type="text" class="form-control" id="patient_name" name="patient_name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="doctor_name">Doctor Name</label>
                        <input type="text" class="form-control" id="doctor_name" name="doctor_name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="appointment_date">Appointment Date</label>
                        <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="appointment_time">Appointment Time</label>
                        <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
                    </div>
                    <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="Pending">Pending</option>
                                    </select>
                                </div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Schedule Appointment</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Life Link Dental. All rights reserved. | <a href="#">Privacy Policy</a></p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
include 'includes/config.php'; // Include your database connection file
$servername = 'localhost';
$username = 'root';
$password = '';
$db = 'dduungu';

$conn = mysqli_connect($servername, $username, $password, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patientName = $_POST['patient_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $doctorName = $_POST['doctor_name'] ?? '';
    $appointmentDate = $_POST['appointment_date'] ?? '';
    $appointmentTime = $_POST['appointment_time'] ?? '';
    $status = $_POST['status'] ?? 'Pending';

    // Save appointment details to the database
    $sql = "INSERT INTO appointments (patient_name, phone, email, doctor_name, appointment_date, appointment_time, status) 
            VALUES ('$patientName', '$phone', '$email', '$doctorName', '$appointmentDate', '$appointmentTime', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Appointment booked successfully.'); window.location.href='home.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>