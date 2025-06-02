<?php
$url = "http://HospitalService:8002/patients";  // API HospitalService Patients endpoint
$response = file_get_contents($url);
$data = json_decode($response, true);

if (isset($data['data']) && is_array($data['data'])) {
    $patients= $data['data'];
} else {
    $patients = [];
}

?>

<!DOCTYPE html>
<html>
<head><title>Patients - HospitalService</title></head>
<body>
<h1>Patients</h1>
<a href="../index.php">Home</a> | <a href="create_patient.php">Add Patient</a>
<table border="1" cellpadding="8" cellspacing="0">
<thead><tr><th>ID</th><th>Name</th><th>Age</th><th>Address</th><th>Phone</th></tr></thead>
<tbody>
<?php if ($patients && is_array($patients)): ?>
    <?php foreach ($patients as $p): ?>
        <tr>
            <td><?=htmlspecialchars($p['id'])?></td>
            <td><?=htmlspecialchars($p['name'])?></td>
            <td><?=htmlspecialchars($p['age'])?></td>
            <td><?=htmlspecialchars($p['address'])?></td>
            <td><?=htmlspecialchars($p['phone'])?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="5">No patients found</td></tr>
<?php endif; ?>
</tbody>
</table>
</body>
</html>
