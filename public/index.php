<?php
session_start();
include_once('includes/header.php');
require_once '../private/database/db.php'; // Ficheiro que liga à DB (PDO em $db)

$current_user_id = $_SESSION['user_id'] ?? null;
?>

<section class="hero">
  <div class="hero-content">
    <h1>Encontra o teu explicador ideal</h1>
    <p>Serviços de apoio escolar, explicações, revisões e muito mais.</p>
    <a href="pages/available_services.php" class="cta-button">Explorar Serviços</a>
  </div>
</section>

<section class="services">
  <h2>Serviços Populares</h2>
  <div class="card-list">
    <?php
    if ($current_user_id) {
        // Se o utilizador estiver logado, mostra serviços de outros utilizadores
        $stmt = $db->prepare("SELECT s.*, u.username FROM services s JOIN users u ON s.user_id = u.id WHERE s.user_id != ? ORDER BY RANDOM() LIMIT 6");
        $stmt->execute([$current_user_id]);
    } else {
        // Se não estiver logado, mostra serviços aleatórios
        $stmt = $db->query("SELECT s.*, u.username FROM services s JOIN users u ON s.user_id = u.id ORDER BY RANDOM() LIMIT 6");
    }
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($services as $service): ?>
      <div class="card">
        <img src="<?= htmlspecialchars($service['image_path'] ?? '/img/default.jpeg') ?>" alt="<?= htmlspecialchars($service['title']) ?>">
        <div class="card-content">
          <h3><?= htmlspecialchars($service['title']) ?></h3>
          <p>por <strong><?= htmlspecialchars($service['username']) ?></strong></p>
          <p class="price">Desde <?= number_format($service['price'], 2) ?>€</p>
          <a href="/pages/view_service.php?id=<?= $service['id'] ?>" class="view-btn">Ver Serviço</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include_once('includes/footer.php'); ?>
