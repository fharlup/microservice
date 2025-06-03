<?php
$url = "http://apotek/orders";
$response = file_get_contents($url);
$data = json_decode($response, true);

if (isset($data['data']) && is_array($data['data'])) {
    $orders = $data['data'];
} else {
    $orders = [];
}
?>

<!DOCTYPE html>
<html>
<head><title>Orders - ApotekService</title></head>
<body>
<h1>Orders</h1>
<a href="../index.php">Home</a> | <a href="create_order.php">Add Order</a>
<table border="1" cellpadding="8" cellspacing="0">
<thead><tr><th>ID</th><th>Supplier ID</th><th>Quantity</th><th>Order Date</th></tr></thead>
<tbody>
<?php if ($orders && is_array($orders)): ?>
    <?php foreach ($orders as $o): ?>
        <tr>
            <td><?=htmlspecialchars($o['id'])?></td>
            <td><?=htmlspecialchars($o['obat_id'])?></td>
            <td><?=htmlspecialchars($o['quantity'])?></td>
            <td><?=htmlspecialchars($o['order_date'])?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="5">No orders found</td></tr>
<?php endif; ?>
</tbody>
</table>
</body>
</html>
