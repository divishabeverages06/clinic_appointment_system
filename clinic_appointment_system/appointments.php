<?php
require "config.php";
$pageTitle="Appointments";
if(isset($_GET["delete"])){
    $id=(int)$_GET["delete"];
    $s=$pdo->prepare("DELETE FROM appointments WHERE id=?");$s->execute([$id]);
    header("Location: appointments.php");exit;
}
if($_SERVER["REQUEST_METHOD"]==="POST" && isset($_POST["status_id"])){
    $s=$pdo->prepare("UPDATE appointments SET status=? WHERE id=?");
    $s->execute([$_POST["status"],(int)$_POST["status_id"]]);
    header("Location: appointments.php");exit;
}
$doctorFilter=(int)($_GET["doctor_id"]??0);$patientFilter=(int)($_GET["patient_id"]??0);$dateFilter=$_GET["date"]??"";
$sql="SELECT a.*,d.name doctor_name,p.name patient_name FROM appointments a JOIN doctors d ON d.id=a.doctor_id JOIN patients p ON p.id=a.patient_id WHERE 1=1";
$params=[];
if($doctorFilter){$sql.=" AND a.doctor_id=?";$params[]=$doctorFilter;}
if($patientFilter){$sql.=" AND a.patient_id=?";$params[]=$patientFilter;}
if($dateFilter){$sql.=" AND a.appointment_date=?";$params[]=$dateFilter;}
$sql.=" ORDER BY a.appointment_date DESC,a.appointment_time DESC";
$s=$pdo->prepare($sql);$s->execute($params);$appointments=$s->fetchAll();
$doctors=$pdo->query("SELECT id,name FROM doctors ORDER BY name")->fetchAll();
$patients=$pdo->query("SELECT id,name FROM patients ORDER BY name")->fetchAll();
require "header.php";
?>
<div class="section-head"><div><h1>Appointments</h1><p class="muted">Book, update, cancel and filter appointments.</p></div><a class="btn" href="appointment_form.php">+ Book Appointment</a></div>

<form class="filter card" method="get">
<select name="doctor_id"><option value="0">All Doctors</option><?php foreach($doctors as $d):?><option value="<?=$d["id"]?>" <?=$doctorFilter==$d["id"]?"selected":""?>><?=htmlspecialchars($d["name"])?></option><?php endforeach;?></select>
<select name="patient_id"><option value="0">All Patients</option><?php foreach($patients as $p):?><option value="<?=$p["id"]?>" <?=$patientFilter==$p["id"]?"selected":""?>><?=htmlspecialchars($p["name"])?></option><?php endforeach;?></select>
<input type="date" name="date" value="<?=htmlspecialchars($dateFilter)?>">
<button class="btn">Filter</button><a class="btn secondary" href="appointments.php">Reset</a>
</form>

<div class="card table-wrap"><table>
<thead><tr><th>Date</th><th>Time</th><th>Doctor</th><th>Patient</th><th>Reason</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
<?php if(!$appointments):?><tr><td colspan="7" class="empty">No appointments found.</td></tr>
<?php else:foreach($appointments as $a):?>
<tr>
<td><?=date("d M Y",strtotime($a["appointment_date"]))?></td><td><?=date("h:i A",strtotime($a["appointment_time"]))?></td>
<td><?=htmlspecialchars($a["doctor_name"])?></td><td><?=htmlspecialchars($a["patient_name"])?></td><td><?=htmlspecialchars($a["reason"])?></td>
<td><form class="inline" method="post"><input type="hidden" name="status_id" value="<?=$a["id"]?>"><select name="status" onchange="this.form.submit()"><?php foreach(["Booked","Completed","Cancelled"] as $st):?><option <?=$a["status"]===$st?"selected":""?>><?=$st?></option><?php endforeach;?></select></form></td>
<td class="actions"><a class="link" href="appointment_form.php?id=<?=$a["id"]?>">Edit</a> <a class="danger" onclick="return confirm('Delete this appointment?')" href="appointments.php?delete=<?=$a["id"]?>">Delete</a></td>
</tr>
<?php endforeach;endif;?>
</tbody></table></div>
<?php require "footer.php";?>
