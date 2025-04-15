<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life Link Hospital</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand, .footer {
            color: #fff !important;
        }
        .navbar-brand img {
            max-height: 50px;
            margin-right: 10px;
        }
        .nav-link {
            color: #fff !important;
        }
        .nav-link.login-btn {
            background-color: #000;
            color: #fff !important;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .nav-link.login-btn:hover {
            background-color: #333;
            color: #fff !important;
        }
        .hero-section {
            background: url('hospital.jpg') no-repeat center center;
            background-size: cover;
            color: #fff;
            padding: 100px 0;
            text-align: center;
            position: relative;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Dark overlay */
            z-index: 1;
        }
        .hero-section .container {
            position: relative;
            z-index: 2;
        }
        .hero-section h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .hero-section p {
            font-size: 1.2rem;
        }
        .appointment-section {
            padding: 50px 0;
        }
        .footer {
            background-color: #343a40;
            padding: 20px 0;
            text-align: center;
        }
        .login-btn {
            font-weight: bold;
            border: 2px solid #fff;
            padding: 8px 20px;
            border-radius: 30px;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background-color: #fff;
            color: #007bff !important;
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="images/logo.png" alt="Life Link Hospital Logo">
                Life Link Dental
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="btn btn-outline-light login-btn" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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
            <form id="appointmentForm" action="appointment.php" method="POST">
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
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Schedule Appointment</button>
            </form>
        </div>
    </section>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Appointment Scheduled</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Your appointment has been successfully scheduled.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Life Link Dental. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#appointmentForm').on('submit', function(event) {
                event.preventDefault(); // Prevent the form from submitting via the browser
                $.ajax({
                    url: 'appointment.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#successModal').modal('show');
                        $('#appointmentForm')[0].reset(); // Reset the form
                    },
                    error: function() {
                        alert('There was an error scheduling your appointment. Please try again.');
                    }
                });
            });
        });
    </script>
</body>
</html>