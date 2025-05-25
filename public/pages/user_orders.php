<?php
require_once '../../private/database/db.php';
session_start();

$viewerId   = $_SESSION['user_id'] ?? 0;
$viewerRole = $_SESSION['role'] ?? '';
$ownerId    = intval($_GET['id'] ?? 0);

if (!$ownerId)            die('Utilizador inválido.');

// Encomendas do utilizador
$stmt = $db->prepare("
  SELECT o.*, s.title
  FROM orders o
  JOIN services s ON s.id = o.service_id
  WHERE o.client_id = ?
  ORDER BY o.created_at DESC
");
$stmt->execute([$ownerId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader();
?>
<link rel="stylesheet" href="/css/orders_view.css">

<div class="orders-view-wrapper">
  <h2 class="orders-view-title">Serviços comprados</h2>

  <?php if (count($orders) === 0): ?>
    <p class="orders-view-empty">Ainda não existem encomendas.</p>
  <?php else: ?>
    <table class="orders-view-table">
      <thead>
        <tr>
          <th>Serviço</th>
          <th>Data</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $o): ?>
          <tr>
            <td>
              <a href="/pages/view_service.php?id=<?= $o['service_id'] ?>">
                <?= htmlspecialchars($o['title']) ?>
              </a>
            </td>
            <td><?= date('d/m/Y', strtotime($o['created_at'])) ?></td>
            <td><?= htmlspecialchars(ucfirst($o['status'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php drawFooter(); ?>