<?php
require_once '../database/db.php';
include_once '../includes/header.php';

// Busca todas as encomendas
$stmt = $db->query("
    SELECT orders.*, users.username, services.title
    FROM orders
    JOIN users ON orders.client_id = users.id
    JOIN services ON orders.service_id = services.id
");
$orders = $stmt->fetchAll();
?>

<h2>Encomendas</h2>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Serviço</th>
        <th>Status</th>
        <th>Data</th>
    </tr>
    <?php foreach ($orders as $order): ?>
    <tr>
        <td><?= $order['id']; ?></td>
        <td><?= htmlspecialchars($order['username']); ?></td>
        <td><?= htmlspecialchars($order['title']); ?></td>
        <td><?= htmlspecialchars($order['status']); ?></td>
        <td><?= htmlspecialchars($order['order_date']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include_once '../includes/footer.php'; ?>
