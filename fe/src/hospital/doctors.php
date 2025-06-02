<?php
$url = "http://localhost:8001/doctors";
$response = file_get_contents($url);
$data = json_decode($response, true);

if (isset($data['data']) && is_array($data['data'])) {
    $doctors= $data['data'];
} else {
    $doctors= [];
}
?>

<!DOCTYPE html>
<html>
<head><title>Doctors - HospitalService</title></head>
<body>
<h1>Doctors</h1>
<a href="../index.php">Home</a> | <a href="create_doctor.php">Add Doctor</a>
<table border="1" cellpadding="8" cellspacing="0">
<thead><tr><th>ID</th><th>Name</th><th>Specialization</th></tr></thead>
<tbody>
<?php if ($doctors && is_array($doctors)): ?>
    <?php foreach ($doctors as $d): ?>
        <tr>
            <td><?=htmlspecialchars($d['id'])?></td>
            <td><?=htmlspecialchars($d['name'])?></td>
            <td><?=htmlspecialchars($d['specialization'])?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="3">No doctors found</td></tr>
<?php endif; ?>
</tbody>
</table>
</body>
</html>
