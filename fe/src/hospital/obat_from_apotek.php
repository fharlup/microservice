<?php
// frontend-obat-from-apotek.php
$response = file_get_contents('http://localhost:8001/obat-from-apotek');
$data = json_decode($response, true);
?>

<h2>Obat from ApotekService</h2>
<?php if ($data['status'] === 'success' && count($data['data']) > 0): ?>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th><th>Name</th><th>Stock</th><th>Price</th>
    </tr>
    <?php foreach ($data['data'] as $obat): ?>
    <tr>
        <td><?= htmlspecialchars($obat['id']) ?></td>
        <td><?= htmlspecialchars($obat['name']) ?></td>
        <td><?= htmlspecialchars($obat['stock']) ?></td>
        <td><?= htmlspecialchars($obat['price']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>No obat data found or error occurred.</p>
<?php endif; ?>
