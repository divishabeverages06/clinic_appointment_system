<?php
require "config.php";
$id=(int)($_GET["id"]??0); $editing=$id>0;
$patient=["name"=>"","gender"=>"","dob"=>"","phone"=>"","email"=>"","address"=>""];
if($editing){$s=$pdo->prepare("SELECT * FROM patients WHERE id=?");$s->execute([$id]);$patient=$s->fetch();if(!$patient)die("Patient not found.");}
$error="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $name=trim($_POST["name"]??"");$gender=$_POST["gender"]??"";$dob=$_POST["dob"]??"";$phone=trim($_POST["phone"]??"");$email=trim($_POST["email"]??"");$address=trim($_POST["address"]??"");
    if($name==="")$error="Patient name is required.";
    else{
        if($editing){$s=$pdo->prepare("UPDATE patients SET name=?,gender=?,dob=?,phone=?,email=?,address=? WHERE id=?");$s->execute([$name,$gender,$dob?:null,$phone,$email,$address,$id]);}
        else{$s=$pdo->prepare("INSERT INTO patients(name,gender,dob,phone,email,address) VALUES(?,?,?,?,?,?)");$s->execute([$name,$gender,$dob?:null,$phone,$email,$address]);}
        header("Location: patients.php");exit;
    }
}
$pageTitle=$editing?"Edit Patient":"Register Patient";require "header.php";
?>
<h1><?=$editing?"Edit Patient":"Register New Patient"?></h1>
<div class="card form-card">
<?php if($error):?><div class="alert error"><?=htmlspecialchars($error)?></div><?php endif;?>
<form method="post">
<label>Patient Name *</label><input name="name" required value="<?=htmlspecialchars($patient["name"])?>">
<label>Gender</label><select name="gender"><option value="">Select</option><?php foreach(["Male","Female","Other"] as $g):?><option <?=$patient["gender"]===$g?"selected":""?>><?=$g?></option><?php endforeach;?></select>
<label>Date of Birth</label><input type="date" name="dob" value="<?=htmlspecialchars($patient["dob"])?>">
<label>Phone</label><input name="phone" value="<?=htmlspecialchars($patient["phone"])?>">
<label>Email</label><input type="email" name="email" value="<?=htmlspecialchars($patient["email"])?>">
<label>Address</label><textarea name="address"><?=htmlspecialchars($patient["address"])?></textarea>
<div class="form-actions"><button class="btn">Save Patient</button><a class="btn secondary" href="patients.php">Cancel</a></div>
</form></div>
<?php require "footer.php";?>
