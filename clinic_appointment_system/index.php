<?php
require "config.php";
$pageTitle = "Dashboard";

$totalDoctors = $pdo->query("SELECT COUNT(*) FROM doctors")->fetchColumn();
$totalPatients = $pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn();
$todayAppointments = $pdo->query("SELECT COUNT(*) FROM appointments WHERE appointment_date = CURDATE()")->fetchColumn();

$today = $pdo->query("
    SELECT a.*, d.name AS doctor_name, p.name AS patient_name
    FROM appointments a
    JOIN doctors d ON d.id = a.doctor_id
    JOIN patients p ON p.id = a.patient_id
    WHERE a.appointment_date = CURDATE()
    ORDER BY a.appointment_time
")->fetchAll();

require "header.php";
?>
<h1>Dashboard</h1>
<p class="muted">Clinic overview and today's appointments.</p>

<div class="cards">
  <div class="card stat"><div class="stat-icon">D</div><div><b><?= $totalDoctors ?></b><span>Total Doctors</span></div></div>
  <div class="card stat"><div class="stat-icon">P</div><div><b><?= $totalPatients ?></b><span>Total Patients</span></div></div>
  <div class="card stat"><div class="stat-icon">A</div><div><b><?= $todayAppointments ?></b><span>Today's Appointments</span></div></div>
</div>

<div class="section-head">
  <h2>Today's Appointments</h2>
  <a class="btn" href="appointment_form.php">+ Book Appointment</a>
</div>

<div class="card table-wrap">
<table>
<thead><tr><th>Time</th><th>Doctor</th><th>Patient</th><th>Status</th></tr></thead>
<tbody>
<?php if (!$today): ?>
<tr><td colspan="4" class="empty">No appointments today.</td></tr>
<?php else: foreach ($today as $a): ?>
<tr>
  <td><?= date("h:i A", strtotime($a["appointment_time"])) ?></td>
  <td><?= htmlspecialchars($a["doctor_name"]) ?></td>
  <td><?= htmlspecialchars($a["patient_name"]) ?></td>
  <td><span class="badge <?= strtolower($a["status"]) ?>"><?= htmlspecialchars($a["status"]) ?></span></td>
</tr>
<?php endforeach; endif; ?>
</tbody>
</table>
</div>
<?php require "footer.php"; ?>
