<?php
if (!isset($pageTitle)) $pageTitle = "Clinic Appointment System";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?></title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
  <div class="brand"><span class="brand-icon">+</span> Clinic Appointment System</div>
  <nav>
    <a href="index.php">Dashboard</a>
    <a href="doctors.php">Doctors</a>
    <a href="patients.php">Patients</a>
    <a href="appointments.php">Appointments</a>
  </nav>
</header>
<main class="container">
