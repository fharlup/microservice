<?php
$url = "http://ApotekService:8002/suppliers";
$response = file_get_contents($url);
$data = json_decode($response, true);

if (isset($data['data']) && is_array($data['data'])) {
    $suppliers= $data['data'];
} else {
    $suppliers= [];
}

?>

<!DOCTYPE html>
<html>
<head><title>Suppliers - ApotekService</title></head>
<body>
<h1>Suppliers</h1>
<a href="../index.php">Home</a> | <a href="create_supplier.php">Add Supplier</a>
<table border="1" cellpadding="8" cellspacing="0">
<thead><tr><th>ID</th><th>Name</th><th>Contact</th></tr></thead>
<tbody>
<?php if ($suppliers && is_array($suppliers)): ?>
    <?php foreach ($suppliers as $s): ?>
        <tr>
            <td><?=htmlspecialchars($s['id'])?></td>
            <td><?=htmlspecialchars($s['name'])?></td>
            <td><?=htmlspecialchars($s['contact'])?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="4">No suppliers found</td></tr>
<?php endif; ?>
</tbody>
</table>
</body>
</html>
