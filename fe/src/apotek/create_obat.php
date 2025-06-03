<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
        'stock' => (int)$_POST['stock'],
        'price' => (float)$_POST['price'],
    ];
    $url = "http://apotek_service/obat";

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
        header("Location: obat.php");
        exit;
    } else {
        $error = "Failed to add obat.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Obat - ApotekService</title></head>
<body>
<h1>Add Obat</h1>
<a href="obat.php">Back to Obat</a>
<?php if (isset($error)): ?>
    <p style="color:red;"><?=$error?></p>
<?php endif; ?>
<form method="POST" action="">
    <label>Name:<br><input type="text" name="name" required></label><br><br>
    <label>Stock:<br><input type="number" name="stock" required></label><br><br>
    <label>Price:<br><input type="number" step="0.01" name="price" required></label><br><br>
    <button type="submit">Add Obat</button>
</form>
</body>
</html>
