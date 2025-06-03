<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
        'age' => (int)$_POST['age'],
        'address' => $_POST['address'],
        'phone' => $_POST['phone'],
    ];
    $url = "http://hospital/patients";

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
        header("Location: patients.php");
        exit;
    } else {
        $error = "Failed to add patient.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Patient - HospitalService</title></head>
<body>
<h1>Add Patient</h1>
<a href="patients.php">Back to Patients</a>
<?php if (isset($error)): ?>
    <p style="color:red;"><?=$error?></p>
<?php endif; ?>
<form method="POST" action="">
    <label>Name:<br><input type="text" name="name" required></label><br><br>
    <label>Age:<br><input type="number" name="age" required></label><br><br>
    <label>Address:<br><input type="text" name="address" required></label><br><br>
    <label>Phone:<br><input type="text" name="phone" required></label><br><br>
    <button type="submit">Add Patient</button>
</form>
</body>
</html>
