<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <h2>Menu</h2>
    <ul>
        <li class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>"><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
        <li class="<?php echo $current_page == 'addpatient.php' ? 'active' : ''; ?>"><a href="addpatient.php"><i class="fas fa-user"></i> ADD PATIENT</a></li>
        <li class="<?php echo $current_page == 'viewpatient.php' ? 'active' : ''; ?>"><a href="viewpatient.php"><i class="fas fa-briefcase"></i> View Patients</a></li>
        <li class="<?php echo $current_page == 'appointment.php' ? 'active' : ''; ?>"><a href="appointment.php"><i class="fas fa-envelope"></i> Appointments</a></li>
        <li class="<?php echo $current_page == 'images.php' ? 'active' : ''; ?>"><a href="image.php"><i class="fas fa-image"></i> Images</a></li>
        <li class="<?php echo $current_page == 'logout.php' ? 'active' : ''; ?>"><a href="login.php"><i class="fas fa-cog"></i> LOGOUT</a></li>
    </ul>
</div>