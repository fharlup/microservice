<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'consultation_id' => (int)$_POST['consultation_id'],
        'medicine_name' => $_POST['medicine_name'],
        'dosage' => $_POST['dosage'],
    ];
    $url = "http://HospitalService:8002/prescriptions";

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
        ],
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result !== FALSE) {
        header("Location: prescriptions.php");
        exit;
    } else {
        $error = "Failed to add prescription.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Prescription - HospitalService</title></head>
<body>
<h1>Add Prescription</h1>
<a href="prescriptions.php">Back to Prescriptions</a>
<?php if (isset($error)): ?>
    <p style="color:red;"><?=$error?></p>
<?php endif; ?>
<form method="POST" action="">
    <label>Consultation ID:<br><input type="number" name="consultation_id" required></label><br><br>
    <label>Obat Name:<br><input type="text" name="medicine_name" required></label><br><br>
    <label>Dosage:<br><input type="text" name="dosage" required></label><br><br>
    <button type="submit">Add Prescription</button>
</form>
</body>
</html>
