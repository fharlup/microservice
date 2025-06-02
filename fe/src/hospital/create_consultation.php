<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'patient_id' => (int)$_POST['patient_id'],
        'doctor_id' => (int)$_POST['doctor_id'],
        'date' => $_POST['date'],
        'symptoms' => $_POST['symptoms'],
        'diagnosis' => $_POST['diagnosis'],
    ];
    $url = "http://HospitalService:8002/consultations";

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
        header("Location: consultations.php");
        exit;
    } else {
        $error = "Failed to add consultation.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Consultation - HospitalService</title></head>
<body>
<h1>Add Consultation</h1>
<a href="consultations.php">Back to Consultations</a>

<?php if (isset($error)): ?>
    <p style="color:red;"><?=$error?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Patient ID:<br><input type="number" name="patient_id" required></label><br><br>
    <label>Doctor ID:<br><input type="number" name="doctor_id" required></label><br><br>
    <label>Date:<br><input type="date" name="date" required></label><br><br>
    <label>Symptoms:<br><textarea name="symptoms" required></textarea></label><br><br>
    <label>Diagnosis:<br><textarea name="diagnosis" required></textarea></label><br><br>
    <button type="submit">Add Consultation</button>
</form>
</body>
</html>
