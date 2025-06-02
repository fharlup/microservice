<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
        'specialization' => $_POST['specialization'],
    ];
    $url = "http://HospitalService:8002/doctors";

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
        header("Location: doctors.php");
        exit;
    } else {
        $error = "Failed to add doctor.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Doctor - HospitalService</title></head>
<body>
<h1>Add Doctor</h1>
<a href="doctors.php">Back to Doctors</a>
<?php if (isset($error)): ?>
    <p style="color:red;"><?=$error?></p>
<?php endif; ?>
<form method="POST" action="">
    <label>Name:<br><input type="text" name="name" required></label><br><br>
    <label>Specialization:<br><input type="text" name="specialization" required></label><br><br>
    <button type="submit">Add Doctor</button>
</form>
</body>
</html>
