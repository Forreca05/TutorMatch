<?php
session_start();
require_once '../database/db.php';

if (!isset($_GET['id'])) {
    die("Serviço não especificado.");
}

$service_id = intval($_GET['id']);

$stmt = $db->prepare("
    SELECT s.*, c.name AS category_name, u.username
    FROM services s
    JOIN categories c ON s.category_id = c.id
    JOIN users u ON s.user_id = u.id
    WHERE s.id = ?
");
$stmt->execute([$service_id]);
$service = $stmt->fetch();

if (!$service) {
    die("Serviço não encontrado.");
}
?>

<?php include_once '../includes/header.php'; ?>
<link rel="stylesheet" href="../css/view_service.css">

<div class="view-service-container">
    <h2><?= htmlspecialchars($service['title']) ?></h2>
    <p class="category"><strong>Categoria:</strong> <?= htmlspecialchars($service['category_name']) ?></p>
    <p class="freelancer"><strong>Prestador:</strong> <?= htmlspecialchars($service['username']) ?></p>
    <p class="description"><?= nl2br(htmlspecialchars($service['description'])) ?></p>
    <p><strong>Preço:</strong> €<?= $service['price'] ?></p>
    <p><strong>Tempo de entrega:</strong> <?= $service['delivery_time'] ?> dias</p>

    <?php if (!empty($service['image_path'])): ?>
        <div class="image-wrapper">
            <img src="<?= $service['image_path'] ?>" alt="Imagem do serviço">
        </div>
    <?php endif; ?>
</div>

<?php include_once '../includes/footer.php'; ?>
