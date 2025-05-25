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

<div class="container">
  <?php drawPageHeader($role === 'freelancer' ? 'Pedidos Recebidos' : 'Minhas Encomendas', 'Gerir as suas encomendas'); ?>

  <?php if (empty($orders)): ?>
    <?php drawEmptyState('Nenhuma encomenda encontrada.', 'Ver Serviços', '/pages/search.php'); ?>
  <?php else: ?>
    <div class="card-list">
      <?php foreach ($orders as $order): ?>
        <div class="card">
          <div class="card-body">
            <h3 class="card-title text-primary mb"><?= htmlspecialchars($order['title']) ?></h3>
            
            <div class="card-content mb">
              <p class="mb-sm"><strong><?= $role === 'freelancer' ? 'Cliente' : 'Freelancer' ?>:</strong> <?= htmlspecialchars($role === 'freelancer' ? $order['client_name'] : $order['freelancer_name']) ?></p>
              <p class="mb-sm"><strong>Estado:</strong> <span class="text-primary font-bold"><?= ucfirst($order['status']) ?></span></p>
              <p class="mb-sm"><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
            </div>
          </div>
          <div class="card-footer">
            <div class="order-actions d-flex gap">
              <form action="chat.php" method="GET" class="d-inline">
                <input type="hidden" name="user_id" value="<?= $user_id ?>">
                <input type="hidden" name="receiver_id" value="<?= $role === 'freelancer' ?
                                                                  $order['client_id'] : $order['freelancer_id'] ?>">
                <button type="submit" class="btn btn-secondary">Chat</button>
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
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php drawFooter(); ?>