<?php
require "config.php";
$pageTitle = "Patients";
if (isset($_GET["delete"])) {
    $id=(int)$_GET["delete"];
    try {
        $stmt=$pdo->prepare("DELETE FROM patients WHERE id=?"); $stmt->execute([$id]);
        header("Location: patients.php?msg=deleted"); exit;
    } catch(PDOException $e) {
        header("Location: patients.php?msg=patient_has_appointments"); exit;
    }
}
$search=trim($_GET["search"] ?? "");
if ($search !== "") {
    $stmt=$pdo->prepare("SELECT * FROM patients WHERE name LIKE ? OR phone LIKE ? ORDER BY name");
    $stmt->execute(["%$search%","%$search%"]); $patients=$stmt->fetchAll();
} else $patients=$pdo->query("SELECT * FROM patients ORDER BY name")->fetchAll();
$msg=$_GET["msg"]??"";
require "header.php";
?>
<div class="section-head"><div><h1>Patients</h1><p class="muted">Register and manage patient details.</p></div><a class="btn" href="patient_form.php">+ Register New Patient</a></div>
<?php if($msg): ?><div class="alert"><?= $msg==="deleted" ? "Patient deleted successfully." : "Patient cannot be deleted because appointments are linked to this patient." ?></div><?php endif; ?>
<form class="filter card" method="get">
<input name="search" value="<?=htmlspecialchars($search)?>" placeholder="Search by patient name or phone">
<button class="btn">Search</button><a class="btn secondary" href="patients.php">Reset</a>
</form>
<div class="card table-wrap"><table>
<thead><tr><th>Name</th><th>Gender</th><th>DOB</th><th>Phone</th><th>Email</th><th>Actions</th></tr></thead>
<tbody>
<?php if(!$patients): ?><tr><td colspan="6" class="empty">No patients found.</td></tr>
<?php else: foreach($patients as $p): ?>
<tr><td><?=htmlspecialchars($p["name"])?></td><td><?=htmlspecialchars($p["gender"])?></td><td><?=htmlspecialchars($p["dob"])?></td><td><?=htmlspecialchars($p["phone"])?></td><td><?=htmlspecialchars($p["email"])?></td>
<td class="actions"><a class="link" href="patient_form.php?id=<?=$p["id"]?>">Edit</a> <a class="danger" onclick="return confirm('Delete this patient?')" href="patients.php?delete=<?=$p["id"]?>">Delete</a></td></tr>
<?php endforeach; endif; ?>
</tbody></table></div>
<?php require "footer.php"; ?>
