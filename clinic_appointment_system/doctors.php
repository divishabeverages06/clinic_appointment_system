<?php
require "config.php";
$pageTitle = "Doctors";

if (isset($_GET["delete"])) {
    $id = (int)$_GET["delete"];
    try {
        $stmt = $pdo->prepare("DELETE FROM doctors WHERE id=?");
        $stmt->execute([$id]);
        header("Location: doctors.php?msg=deleted"); exit;
    } catch (PDOException $e) {
        header("Location: doctors.php?msg=doctor_has_appointments"); exit;
    }
}
$specialization = trim($_GET["specialization"] ?? "");
if ($specialization !== "") {
    $stmt = $pdo->prepare("SELECT * FROM doctors WHERE specialization LIKE ? ORDER BY name");
    $stmt->execute(["%$specialization%"]);
    $doctors = $stmt->fetchAll();
} else {
    $doctors = $pdo->query("SELECT * FROM doctors ORDER BY name")->fetchAll();
}
$msg = $_GET["msg"] ?? "";
require "header.php";
?>
<div class="section-head">
  <div><h1>Doctors</h1><p class="muted">Add, view, edit and delete doctors.</p></div>
  <a class="btn" href="doctor_form.php">+ Add New Doctor</a>
</div>

<?php if ($msg): ?><div class="alert"><?= $msg === "deleted" ? "Doctor deleted successfully." : "Doctor cannot be deleted because appointments are linked to this doctor." ?></div><?php endif; ?>

<form class="filter card" method="get">
  <input name="specialization" value="<?= htmlspecialchars($specialization) ?>" placeholder="Filter by specialization">
  <button class="btn" type="submit">Filter</button>
  <a class="btn secondary" href="doctors.php">Reset</a>
</form>

<div class="card table-wrap">
<table>
<thead><tr><th>Name</th><th>Specialization</th><th>Phone</th><th>Email</th><th>Actions</th></tr></thead>
<tbody>
<?php if (!$doctors): ?><tr><td colspan="5" class="empty">No doctors found.</td></tr>
<?php else: foreach ($doctors as $d): ?>
<tr>
<td><?= htmlspecialchars($d["name"]) ?></td>
<td><?= htmlspecialchars($d["specialization"]) ?></td>
<td><?= htmlspecialchars($d["phone"]) ?></td>
<td><?= htmlspecialchars($d["email"]) ?></td>
<td class="actions"><a class="link" href="doctor_form.php?id=<?= $d["id"] ?>">Edit</a>
<a class="danger" onclick="return confirm('Delete this doctor?')" href="doctors.php?delete=<?= $d["id"] ?>">Delete</a></td>
</tr>
<?php endforeach; endif; ?>
</tbody></table></div>
<?php require "footer.php"; ?>
