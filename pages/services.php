<?php
session_start();
require_once '../database/db.php';
include_once '../includes/header.php';

// Busca todos os serviços da base de dados
$stmt = $db->query("SELECT s.*, u.username FROM services s JOIN users u ON s.user_id = u.id");
$services = $stmt->fetchAll();
?>

<h2>Serviços Disponíveis</h2>

<div class="services-container">
  <?php if (count($services) > 0): ?>
    <?php foreach ($services as $service): ?>
      <div class="service-card">
        <img src="<?php echo htmlspecialchars($service['image'] ?: '../img/default_service.jpg'); ?>" alt="Imagem do Serviço" class="service-img">
        <h3><?php echo htmlspecialchars($service['title']); ?></h3>
        <p><?php echo htmlspecialchars($service['description']); ?></p>
        <p><strong>Preço:</strong> <?php echo htmlspecialchars($service['price']); ?>€</p>
        <p><small>Publicado por: <?php echo htmlspecialchars($service['username']); ?></small></p>
        <a href="view_service.php?id=<?php echo $service['id']; ?>" class="btn">Ver Mais</a>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>Não há serviços disponíveis de momento.</p>
  <?php endif; ?>
</div>

<?php include_once '../includes/footer.php'; ?>