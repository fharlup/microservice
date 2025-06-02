
<?php
$url = "http://localhost:8001/consultations";
$response = file_get_contents($url);
$data = json_decode($response, true);

if (isset($data['data']) && is_array($data['data'])) {
    $consultations = $data['data'];
} else {
    $consultations = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consultations - HospitalService</title>
</head>
<body>
    <h1>Consultations</h1>
    <a href="../index.php">Home</a> | <a href="create_consultation.php">Add Consultation</a>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient ID</th>
                <th>Doctor ID</th>
                <th>Date</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($consultations && is_array($consultations)): ?>
            <?php foreach ($consultations as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['id']) ?></td>
                    <td><?= htmlspecialchars($c['patient_id']) ?></td>
                    <td><?= htmlspecialchars($c['doctor_id']) ?></td>
                    <td><?= htmlspecialchars($c['date']) ?></td>
                    <td><?= htmlspecialchars($c['notes'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">No consultations found</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
