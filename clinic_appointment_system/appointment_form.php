<?php
require "config.php";
$id=(int)($_GET["id"]??0);$editing=$id>0;
$a=["doctor_id"=>"","patient_id"=>"","appointment_date"=>date("Y-m-d"),"appointment_time"=>"09:00","reason"=>"","status"=>"Booked"];
if($editing){$s=$pdo->prepare("SELECT * FROM appointments WHERE id=?");$s->execute([$id]);$a=$s->fetch();if(!$a)die("Appointment not found.");}
$doctors=$pdo->query("SELECT id,name,specialization FROM doctors ORDER BY name")->fetchAll();
$patients=$pdo->query("SELECT id,name FROM patients ORDER BY name")->fetchAll();
$error="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $doctor=(int)$_POST["doctor_id"];$patient=(int)$_POST["patient_id"];$date=$_POST["appointment_date"];$time=$_POST["appointment_time"];$reason=trim($_POST["reason"]??"");$status=$_POST["status"]??"Booked";
    if(!$doctor||!$patient||!$date||!$time)$error="Doctor, patient, date and time are required.";
    else{
        if($editing){$s=$pdo->prepare("UPDATE appointments SET doctor_id=?,patient_id=?,appointment_date=?,appointment_time=?,reason=?,status=? WHERE id=?");$s->execute([$doctor,$patient,$date,$time,$reason,$status,$id]);}
        else{$s=$pdo->prepare("INSERT INTO appointments(doctor_id,patient_id,appointment_date,appointment_time,reason,status) VALUES(?,?,?,?,?,?)");$s->execute([$doctor,$patient,$date,$time,$reason,$status]);}
        header("Location: appointments.php");exit;
    }
}
$pageTitle=$editing?"Edit Appointment":"Book Appointment";require "header.php";
?>
<h1><?=$editing?"Edit Appointment":"Book New Appointment"?></h1>
<div class="card form-card">
<?php if($error):?><div class="alert error"><?=htmlspecialchars($error)?></div><?php endif;?>
<?php if(!$doctors || !$patients):?><div class="alert error">Please add at least one doctor and one patient before booking an appointment.</div><?php endif;?>
<form method="post">
<label>Doctor *</label><select name="doctor_id" required><option value="">Select doctor</option><?php foreach($doctors as $d):?><option value="<?=$d["id"]?>" <?=$a["doctor_id"]==$d["id"]?"selected":""?>><?=htmlspecialchars($d["name"])?> — <?=htmlspecialchars($d["specialization"])?></option><?php endforeach;?></select>
<label>Patient *</label><select name="patient_id" required><option value="">Select patient</option><?php foreach($patients as $p):?><option value="<?=$p["id"]?>" <?=$a["patient_id"]==$p["id"]?"selected":""?>><?=htmlspecialchars($p["name"])?></option><?php endforeach;?></select>
<label>Appointment Date *</label><input type="date" name="appointment_date" required value="<?=htmlspecialchars($a["appointment_date"])?>">
<label>Appointment Time *</label><input type="time" name="appointment_time" required value="<?=htmlspecialchars(substr($a["appointment_time"],0,5))?>">
<label>Reason / Notes</label><textarea name="reason"><?=htmlspecialchars($a["reason"])?></textarea>
<label>Status</label><select name="status"><?php foreach(["Booked","Completed","Cancelled"] as $st):?><option <?=$a["status"]===$st?"selected":""?>><?=$st?></option><?php endforeach;?></select>
<div class="form-actions"><button class="btn">Save Appointment</button><a class="btn secondary" href="appointments.php">Cancel</a></div>
</form></div>
<?php require "footer.php";?>
