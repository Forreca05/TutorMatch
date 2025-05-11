<?php
// File: my_services.php
session_start();
require_once '../database/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') {
    header('Location: ../pages/login.php');
    exit;
}

$stmt = $db->prepare("SELECT * FROM services WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$services = $stmt->fetchAll();
?>

<?php include_once '../includes/header.php'; ?>
<h2>Meus Serviços</h2>
<?php foreach ($services as $service): ?>
  <div class="service">
    <h3><?= htmlspecialchars($service['title']) ?></h3>
    <p><?= htmlspecialchars($service['description']) ?></p>
    <p>Preço: €<?= $service['price'] ?> | Entrega: <?= $service['delivery_time'] ?> dias</p>
    <?php if ($service['image_path']): ?>
      <img src="<?= $service['image_path'] ?>" alt="Imagem do serviço" width="150">
    <?php endif; ?>
  </div>
<?php endforeach; ?>
<?php include_once '../includes/footer.php'; ?>