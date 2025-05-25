<?php
require_once '../../private/database/db.php';
session_start();

$userId = intval($_GET['id'] ?? 0);
if (!$userId) die('Utilizador inválido.');

$stmt = $db->prepare("
  SELECT s.*, c.name AS category 
  FROM services s 
  LEFT JOIN categories c ON s.category_id = c.id
  WHERE s.user_id = ?
  ORDER BY s.created_at DESC
");
$stmt->execute([$userId]);
$services = $stmt->fetchAll();
require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader();
?>
<h2>Serviços oferecidos por <?= htmlspecialchars($services[0]['username'] ?? 'Utilizador') ?></h2>

<link rel="stylesheet" href="/css/services.css">
<div class="services-grid">
  <?php foreach ($services as $s): ?>
    <div class="service-card">
      <img src="<?= htmlspecialchars($s['image_path'] ?: '/img/default.jpeg') ?>" class="service-img">
      <div class="service-info">
        <h3><?= htmlspecialchars($s['title']) ?></h3>
        <p class="price"><?= number_format($s['price'],2) ?> €</p>
        <a href="/pages/view_service.php?id=<?= $s['id'] ?>" class="btn">Ver</a>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php drawFooter(); ?>
