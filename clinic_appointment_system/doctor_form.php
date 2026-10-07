<?php
require "config.php";
$id = (int)($_GET["id"] ?? 0);
$editing = $id > 0;
$doctor = ["name"=>"","specialization"=>"","phone"=>"","email"=>"","address"=>""];
if ($editing) {
    $stmt = $pdo->prepare("SELECT * FROM doctors WHERE id=?");
    $stmt->execute([$id]);
    $doctor = $stmt->fetch();
    if (!$doctor) die("Doctor not found.");
}
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $spec = trim($_POST["specialization"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $address = trim($_POST["address"] ?? "");
    if ($name === "" || $spec === "") $error = "Name and specialization are required.";
    else {
        if ($editing) {
            $stmt = $pdo->prepare("UPDATE doctors SET name=?, specialization=?, phone=?, email=?, address=? WHERE id=?");
            $stmt->execute([$name,$spec,$phone,$email,$address,$id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO doctors(name,specialization,phone,email,address) VALUES(?,?,?,?,?)");
            $stmt->execute([$name,$spec,$phone,$email,$address]);
        }
        header("Location: doctors.php"); exit;
    }
}
$pageTitle = $editing ? "Edit Doctor" : "Add Doctor";
require "header.php";
?>
<h1><?= $editing ? "Edit Doctor" : "Add New Doctor" ?></h1>
<div class="card form-card">
<?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post">
<label>Doctor Name *</label><input name="name" required value="<?= htmlspecialchars($doctor["name"]) ?>">
<label>Specialization *</label><input name="specialization" required value="<?= htmlspecialchars($doctor["specialization"]) ?>" placeholder="e.g. Cardiologist">
<label>Phone</label><input name="phone" value="<?= htmlspecialchars($doctor["phone"]) ?>">
<label>Email</label><input type="email" name="email" value="<?= htmlspecialchars($doctor["email"]) ?>">
<label>Address</label><textarea name="address"><?= htmlspecialchars($doctor["address"]) ?></textarea>
<div class="form-actions"><button class="btn" type="submit">Save Doctor</button><a class="btn secondary" href="doctors.php">Cancel</a></div>
</form>
</div>
<?php require "footer.php"; ?>
