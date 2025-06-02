<?php
$url = "http://HospitalService:8002/prescriptions";
$response = file_get_contents($url);
$data = json_decode($response, true);
if (isset($data['data']) && is_array($data['data'])) {
    $prescriptions= $data['data'];
} else {
    $prescriptions= [];
}
?>

<!DOCTYPE html>
<html>
<head><title>Prescriptions - HospitalService</title></head>
<body>
<h1>Prescriptions</h1>
<a href="../index.php">Home</a> | <a href="create_prescription.php">Add Prescription</a>
<table border="1" cellpadding="8" cellspacing="0">
<thead><tr><th>ID</th><th>Consultation ID</th><th>Obat Name</th><th>Dosage</th></tr></thead>
<tbody>
<?php if ($prescriptions && is_array($prescriptions)): ?>
    <?php foreach ($prescriptions as $p): ?>
        <tr>
            <td><?=htmlspecialchars($p['id'])?></td>
            <td><?=htmlspecialchars($p['consultation_id'])?></td>
<td><?=htmlspecialchars($p['medicine_name'] ?? '')?></td>
            <td><?=htmlspecialchars($p['dosage'])?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="4">No prescriptions found</td></tr>
<?php endif; ?>
</tbody>
</table>
</body>
</html>
