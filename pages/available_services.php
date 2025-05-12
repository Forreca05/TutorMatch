<?php
session_start();
require_once '../database/db.php';
include_once '../includes/header.php';

$stmt = $db->query("SELECT s.*, u.username FROM services s JOIN users u ON s.user_id = u.id");
$services = $stmt->fetchAll();
?>

<link rel="stylesheet" href="../css/services.css">

<div class="page-title">
  <h2>Serviços Disponíveis</h2>
</div>

<div class="services-grid">
  <?php if (count($services) > 0): ?>
    <?php foreach ($services as $service): ?>
      <div class="service-card">
        <img src="<?= htmlspecialchars($service['image_path'] ?: '../img/default_service.jpg'); ?>" alt="Imagem do Serviço" class="service-img">
        <div class="service-info">
          <h3><?= htmlspecialchars($service['title']); ?></h3>
          <p class="description"><?= htmlspecialchars(substr($service['description'], 0, 100)) ?>...</p>
          <p class="price"><strong>Preço:</strong> €<?= $service['price']; ?></p>
          <p class="author"><small>Por: <?= htmlspecialchars($service['username']); ?></small></p>
          <a href="view_service.php?id=<?= $service['id']; ?>" class="btn">Ver Mais</a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="no-services">Não há serviços disponíveis de momento.</p>
  <?php endif; ?>
</div>

<?php include_once '../includes/footer.php'; ?>
