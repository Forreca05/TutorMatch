<?php
session_start();
require_once '../../private/database/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'client';

if ($role === 'freelancer') {
  // Freelancers veem pedidos recebidos
  $stmt = $db->prepare("SELECT o.*, s.title, u.username AS client_name
                        FROM orders o
                        JOIN (
                            SELECT client_id, MAX(created_at) AS max_created
                            FROM orders
                            WHERE freelancer_id = ?
                            GROUP BY client_id
                        ) latest ON o.client_id = latest.client_id AND o.created_at = latest.max_created
                        JOIN services s ON o.service_id = s.id
                        JOIN users u ON o.client_id = u.id
                        ORDER BY o.created_at DESC");
  $stmt->execute([$user_id]);
  $orders = $stmt->fetchAll();
} else {
  // Clientes veem suas encomendas
  $stmt = $db->prepare("SELECT o.*, s.title, u.username AS freelancer_name
                        FROM orders o
                        JOIN (
                            SELECT freelancer_id, MAX(created_at) AS max_created
                            FROM orders
                            WHERE client_id = ?
                            GROUP BY freelancer_id
                        ) latest ON o.freelancer_id = latest.freelancer_id AND o.created_at = latest.max_created
                        JOIN services s ON o.service_id = s.id
                        JOIN users u ON o.freelancer_id = u.id
                        ORDER BY o.created_at DESC");
  $stmt->execute([$user_id]);
  $orders = $stmt->fetchAll();
}
?>

<?php include_once '../includes/header.php'; ?>
<link rel="stylesheet" href="../css/orders.css">

<div class="orders-container">
  <h2><?= $role === 'freelancer' ? 'Pedidos Recebidos' : 'Minhas Encomendas' ?></h2>

  <?php if (empty($orders)): ?>
    <p>Nenhuma encomenda encontrada.</p>
  <?php else: ?>
    <div class="orders-list">
      <?php foreach ($orders as $order): ?>
        <div class="order-card">
          <h3><?= htmlspecialchars($order['title']) ?></h3>
          <p><strong><?= $role === 'freelancer' ? 'Cliente' : 'Freelancer' ?>:</strong> <?= htmlspecialchars($role === 'freelancer' ? $order['client_name'] : $order['freelancer_name']) ?></p>
          <p><strong>Estado:</strong> <?= ucfirst($order['status']) ?></p>
          <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>

          <?php if ($role === 'freelancer' && $order['status'] === 'pending'): ?>
            <form action="../actions/update_order_status.php" method="POST" class="status-form">
              <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
              <button name="action" value="accepted">Aceitar</button>
              <button name="action" value="rejected">Rejeitar</button>
            </form>
          <?php elseif ($role === 'client' && $order['status'] === 'accepted'): ?>
            <form action="order_service.php" method="GET">
              <input type="hidden" name="id" value="<?= $order['service_id'] ?>">
              <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
              <button type="submit">Proceder para o Pagamento</button>
            </form>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php include_once '../includes/footer.php'; ?>
