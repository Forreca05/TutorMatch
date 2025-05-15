<?php
session_start();
require_once '../database/db.php';

if (!isset($_GET['id'])) {
    die("Serviço não especificado.");
}

$service_id = intval($_GET['id']);

$stmt = $db->prepare("
    SELECT s.*, c.name AS category_name, u.username, u.id AS freelancer_id
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

    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'client'): ?>
        <a href="../pages/order.php?id=<?= $service_id ?>" class="btn" style="margin-top: 10px; display: inline-block;">Encomendar Serviço</a>
        <a href="../pages/chat.php?receiver_id=<?= $service['freelancer_id'] ?>" class="btn" style="margin-top: 10px; display: inline-block;">Enviar Mensagem</a>
    <?php endif; ?>

    <div class="review-section">
        <h3>Avaliações</h3>
        <?php
        $review_stmt = $db->prepare("SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.service_id = ? ORDER BY r.created_at DESC");
        $review_stmt->execute([$service_id]);
        $reviews = $review_stmt->fetchAll();
        ?>

        <?php foreach ($reviews as $review): ?>
            <div class="review">
                <strong><?= htmlspecialchars($review['username']) ?></strong> - <?= $review['rating'] ?>/5
                <p><?= htmlspecialchars($review['comment']) ?></p>
            </div>
        <?php endforeach; ?>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'client'): ?>
            <form action="../actions/submit_review.php" method="POST" class="review-form">
                <h4>Deixe uma avaliação:</h4>
                <input type="hidden" name="service_id" value="<?= $service_id ?>">
                <label for="rating">Classificação:</label>
                <select name="rating" id="rating" required>
                    <option value="">Escolha...</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>

                <label for="comment">Comentário:</label>
                <textarea name="comment" id="comment" required></textarea>
                <button type="submit">Submeter Avaliação</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>