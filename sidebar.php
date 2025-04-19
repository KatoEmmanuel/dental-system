<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <h2><img src="images/logo.png" alt="Logo" style="width: 50px;"> Dental System</h2>
    <ul>
        <li class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>"><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
        <li class="<?php echo $current_page == 'addpatient.php' ? 'active' : ''; ?>"><a href="addpatient.php"><i class="fas fa-user"></i> ADD PATIENT</a></li>
        <li class="<?php echo $current_page == 'viewpatient.php' ? 'active' : ''; ?>"><a href="viewpatient.php"><i class="fas fa-briefcase"></i> View Patients</a></li>
        <li class="<?php echo $current_page == 'appointment.php' ? 'active' : ''; ?>"><a href="appointment.php"><i class="fas fa-envelope"></i> Appointments</a></li>
        <li class="<?php echo $current_page == 'images.php' ? 'active' : ''; ?>"><a href="image.php"><i class="fas fa-image"></i> Images</a></li>
        <li class="<?php echo $current_page == 'logout.php' ? 'active' : ''; ?>"><a href="logout.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a></li>
    </ul>
</div>

<style>
.sidebar {
    width: 250px;
    background-color: #343a40; /* Dark background */
    color: #fff;
    height: 100vh;
    position: fixed;
    padding: 20px;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 24px;
    color: #ffc107; /* Highlighted color */
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar ul li {
    margin: 15px 0;
}

.sidebar ul li a {
    text-decoration: none;
    color: #fff;
    font-size: 18px;
    display: flex;
    align-items: center;
}

.sidebar ul li a i {
    margin-right: 10px;
}

.sidebar ul li.active a {
    background-color: #ffc107; /* Highlight active link */
    color: #343a40;
    padding: 10px;
    border-radius: 5px;
}

.sidebar ul li a:hover {
    background-color: #495057; /* Hover effect */
    padding: 10px;
    border-radius: 5px;
}

@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }

    .sidebar ul {
        display: flex;
        flex-direction: row;
        justify-content: space-around;
    }

    .sidebar ul li {
        margin: 0;
    }
}
</style>