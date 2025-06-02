<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
        'contact' => $_POST['contact'],
        'address' => $_POST['address'],
    ];
    $url = "http://ApotekService:8002/suppliers";

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
        header("Location: suppliers.php");
        exit;
    } else {
        $error = "Failed to add supplier.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Supplier - ApotekService</title></head>
<body>
<h1>Add Supplier</h1>
<a href="suppliers.php">Back to Suppliers</a>
<?php if (isset($error)): ?>
    <p style="color:red;"><?=$error?></p>
<?php endif; ?>
<form method="POST" action="">
    <label>Name:<br><input type="text" name="name" required></label><br><br>
    <label>Contact:<br><input type="text" name="contact" required></label><br><br>
    <label>Address:<br><input type="text" name="address" required></label><br><br>
    <button type="submit">Add Supplier</button>
</form>
</body>
</html>
