<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'supplier_id' => (int)$_POST['supplier_id'],
        'obat_id' => (int)$_POST['obat_id'],
        'quantity' => (int)$_POST['quantity'],
        'order_date' => $_POST['order_date'],
    ];
    $url = "http://apotek/orders";

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
        header("Location: orders.php");
        exit;
    } else {
        $error = "Failed to add order.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Order - ApotekService</title></head>
<body>
<h1>Add Order</h1>
<a href="orders.php">Back to Orders</a>
<?php if (isset($error)): ?>
    <p style="color:red;"><?=$error?></p>
<?php endif; ?>
<form method="POST" action="">
    <label>Supplier ID:<br><input type="number" name="supplier_id" required></label><br><br>
    <label>Obat ID:<br><input type="number" name="obat_id" required></label><br><br>
    <label>Quantity:<br><input type="number" name="quantity" required></label><br><br>
    <label>Order Date:<br><input type="date" name="order_date" required></label><br><br>
    <button type="submit">Add Order</button>
</form>
</body>
</html>
