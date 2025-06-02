<?php
$url = "http://localhost:8002/obat";
$response = file_get_contents($url);
$data = json_decode($response, true);

if (isset($data['data']) && is_array($data['data'])) {
    $obats = $data['data'];
} else {
    $obats = [];
}
?>

<!DOCTYPE html>
<html>
<head><title>Obat - ApotekService</title></head>
<body>
<h1>Obat</h1>
<a href="../index.php">Home</a> | <a href="create_obat.php">Add Obat</a>
<table border="1" cellpadding="8" cellspacing="0">
<thead><tr><th>ID</th><th>Name</th><th>Stock</th><th>Price</th></tr></thead>
<tbody>
<?php if ($obats && is_array($obats)): ?>
    <?php foreach ($obats as $o): ?>
        <tr>
            <td><?=htmlspecialchars($o['id'])?></td>
            <td><?=htmlspecialchars($o['name'])?></td>
            <td><?=htmlspecialchars($o['stock'])?></td>
            <td><?=htmlspecialchars($o['price'])?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="4">No obat found</td></tr>
<?php endif; ?>
</tbody>
</table>
</body>
</html>
