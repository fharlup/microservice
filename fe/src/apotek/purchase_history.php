<?php
$url = "http://apotek_service/purchase-history"; // URL sudah benar
$response = file_get_contents($url);
$data = json_decode($response, true); // decode JSON

if (isset($data['data']) && is_array($data['data'])) {
    $histories = $data['data'];
} else {
    $histories = [];
}
?>

<!DOCTYPE html>
<html>
<head><title>Purchase History - ApotekService</title></head>
<body>
<h1>Purchase History</h1>
<a href="../index.php">Home</a> | <a href="create_purchase_history.php">Add Purchase History</a>
<table border="1" cellpadding="8" cellspacing="0">
<thead>
<tr>
    <th>ID</th>
    <th>Patient ID</th>
    <th>Medicine Name</th>
    <th>Quantity</th>
    <th>Purchase Date</th>
</tr>
</thead>
<tbody>
<?php if ($histories): ?>
    <?php foreach ($histories as $h): ?>
        <tr>
            <td><?=htmlspecialchars($h['id'])?></td>
            <td><?=htmlspecialchars($h['patient_id'])?></td>
            <td><?=htmlspecialchars($h['medicine_name'])?></td>
            <td><?=htmlspecialchars($h['quantity'])?></td>
            <td><?=htmlspecialchars($h['purchase_date'])?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="5">No purchase history found</td></tr>
<?php endif; ?>
</tbody>
</table>
</body>
</html>
