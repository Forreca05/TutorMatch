<?php
session_start();
require_once '../../private/database/db.php';
require_once(__DIR__ . '/../templates/service_card.tpl.php');

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

<?php require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader(); ?>

<div class="container">
  <?php drawPageHeader('Meus Serviços', 'Gerir os seus serviços criados', 
    '<a href="create_service.php" class="btn btn-primary">Criar Novo Serviço</a>'); ?>

  <?php if (empty($grouped)): ?>
    <?php drawEmptyState('Ainda não criaste nenhum serviço.', 'Criar Primeiro Serviço', 'create_service.php'); ?>
  <?php else: ?>
    <?php foreach ($grouped as $category => $services): ?>
      <div class="category-group">
        <h3><?= htmlspecialchars($category) ?></h3>
        <div class="card-list">
          <?php foreach ($services as $service): 
            drawServiceCard($service, false, true, 'freelancer');
          endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php drawFooter(); ?>