<?php
session_start();
require_once '../../private/database/db.php';
require_once(__DIR__ . '/../../private/utils/csrf.php');

if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'client';

if ($role === 'freelancer') {
  $stmt = $db->prepare("
    SELECT o.*, s.title, u.username AS client_name
      FROM orders o
      JOIN services s  ON o.service_id    = s.id
      JOIN users    u  ON o.client_id     = u.id
     WHERE o.freelancer_id = ?
     ORDER BY o.created_at DESC
  ");
  $stmt->execute([$user_id]);
  $orders = $stmt->fetchAll();
} else {
  $stmt = $db->prepare("
    SELECT o.*, s.title, u.username AS freelancer_name
      FROM orders o
      JOIN services s  ON o.service_id     = s.id
      JOIN users    u  ON o.freelancer_id  = u.id
     WHERE o.client_id     = ?
     ORDER BY o.created_at DESC
  ");
  $stmt->execute([$user_id]);
  $orders = $stmt->fetchAll();
}

?>

<?php require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader(); ?>
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
          <form action="chat.php" method="GET">
            <input type="hidden" name="user_id" value="<?= $user_id?>">
            <input type="hidden" name="receiver_id" value="<?= $role === 'freelancer' ? 
            $order['client_id'] : $order['freelancer_id']?>">
            <button type="submit">Chat</button>
          </form>

          <?php if ($role === 'freelancer' && $order['status'] === 'Pendente'): ?>
            <form action="../actions/accept_order.php" method="POST" class="status-form">
              <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
              <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
              <button type="submit" name="action" value="Aceite">Aceitar</button>
              <button type="submit" name="action" value="Rejeitado">Rejeitar</button>
            </form>
          <?php elseif ($role === 'client' && $order['status'] === 'Aceite'): ?>
              <form action="pay_service.php" method="GET">
                <input type="hidden" name="id" value="<?= $order['service_id'] ?>">
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                <button type="submit">Proceder para o Pagamento</button>
              </form>
          <?php elseif ($role === 'freelancer' && $order['status'] === 'Pago'): ?>
              <form action="../actions/deliver_order.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                <button type="submit">Entregar</button>
              </form>
          <?php elseif ($role === 'client' && $order['status'] === 'Entregue'): ?>
            <form action="../actions/complete_order.php" method="POST">
              <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
              <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
              <button type="submit" name="action" value="Aceite">Marcar como concluído</button>
              <button type="submit" name="action" value="Rejeitado">Rejeitar</button>
            </form>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php drawFooter(); ?>
