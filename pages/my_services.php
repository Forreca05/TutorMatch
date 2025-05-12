<?php
session_start();
require_once '../database/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') {
    header('Location: ../pages/login.php');
    exit;
}

// Buscar os serviços com nome da categoria
$stmt = $db->prepare("
    SELECT s.*, c.name AS category_name
    FROM services s
    JOIN categories c ON s.category_id = c.id
    WHERE s.user_id = ?
    ORDER BY c.name ASC, s.title ASC
");
$stmt->execute([$_SESSION['user_id']]);
$services = $stmt->fetchAll();

// Agrupar serviços por categoria
$grouped = [];
foreach ($services as $service) {
    $grouped[$service['category_name']][] = $service;
}
?>

<?php include_once '../includes/header.php'; ?>
<link rel="stylesheet" href="../css/services.css">

<div class="my-services-container">
  <h2>Meus Serviços</h2>

  <?php if (empty($grouped)): ?>
    <p>Ainda não criaste nenhum serviço.</p>
  <?php else: ?>
    <?php foreach ($grouped as $category => $services): ?>
      <div class="category-group">
        <h3><?= htmlspecialchars($category) ?></h3>
        <div class="service-list">
          <?php foreach ($services as $service): ?>
            <div class="service-card">
              <h4><?= htmlspecialchars($service['title']) ?></h4>
              <p class="description"><?= htmlspecialchars($service['description']) ?></p>
              <p><strong>Preço:</strong> €<?= $service['price'] ?></p>
              <p><strong>Entrega:</strong> <?= $service['delivery_time'] ?> dias</p>
              <?php if (!empty($service['image_path'])): ?>
                <img src="<?= $service['image_path'] ?>" alt="Imagem do serviço">
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php include_once '../includes/footer.php'; ?>
