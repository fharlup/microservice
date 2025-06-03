<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'patient_id' => (int)$_POST['patient_id'],
        'medicine_name' => $_POST['medicine_name'],
        'quantity' => (int)$_POST['quantity'],
        'purchase_date' => $_POST['purchase_date'],
    ];
    $url = "http://apotek/purchase-history";

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
        header("Location: purchase_history.php");
        exit;
    } else {
        $error = "Failed to add purchase history.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Purchase History - ApotekService</title></head>
<body>
<h1>Add Purchase History</h1>
<a href="purchase_history.php">Back to Purchase History</a>
<?php if (isset($error)): ?>
    <p style="color:red;"><?=$error?></p>
<?php endif; ?>
<form method="POST" action="">
    <label>Patient ID:<br><input type="number" name="patient_id" required></label><br><br>
    <label>Medicine Name:<br><input type="text" name="medicine_name" required></label><br><br>
    <label>Quantity:<br><input type="number" name="quantity" required></label><br><br>
    <label>Purchase Date:<br><input type="date" name="purchase_date" required></label><br><br>
    <button type="submit">Add Purchase History</button>
</form>
</body>
</html>
