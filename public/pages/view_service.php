<?php
session_start();
require_once '../../private/database/db.php';

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

$rating_stmt = $db->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS total_reviews FROM reviews WHERE service_id = ?");
$rating_stmt->execute([$service_id]);
$ratingData = $rating_stmt->fetch();

$averageRating = $ratingData['avg_rating'] !== null ? round($ratingData['avg_rating'], 1) : 0;
$totalReviews = (int)$ratingData['total_reviews'];

$order_id = null;
?>

<?php require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader(); ?>
<link rel="stylesheet" href="../css/view_service.css">
<link rel="stylesheet" href="../css/register.css">

<div class="view-service-container">
    <h2><?= htmlspecialchars($service['title']) ?></h2>
    <p class="category"><strong>Categoria:</strong> <?= htmlspecialchars($service['category_name']) ?></p>
    <p class="freelancer"><strong>Prestador:</strong> <?= htmlspecialchars($service['username']) ?></p>
    <p class="description"><?= nl2br(htmlspecialchars($service['description'])) ?></p>
    <p><strong>Preço:</strong> <?= number_format($service['price'], 2, ',', '.') ?>€</p>
    <p><strong>Tempo de entrega:</strong> <?= htmlspecialchars($service['delivery_time']) ?> dias</p>

    <?php if (!empty($service['image_path'])): ?>
        <div class="image-wrapper">
            <img src="<?= htmlspecialchars($service['image_path']) ?>" alt="Imagem do serviço">
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'client'): ?>
        <a href="#" id="order-link" class="btn" style="margin-top: 10px; display: inline-block;">Encomendar Serviço</a>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $service['freelancer_id']): ?>
        <a href="../pages/edit_service.php?id=<?= $service_id ?>" class="btn" style="margin-top: 10px; display: inline-block;">Editar Serviço</a>
    <?php endif; ?>

    <div class="review-section">
        <h3>Avaliações</h3>

        <?php if (isset($_GET['review']) && $_GET['review'] === 'success'): ?>
            <p class="flash-message success">Avaliação submetida com sucesso!</p>
        <?php elseif (isset($_GET['review']) && $_GET['review'] === 'error'): ?>
            <p class="flash-message error">Erro ao submeter avaliação.</p>
        <?php endif; ?>

        <?php
        $review_stmt = $db->prepare("
            SELECT r.*, u.username 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.service_id = ? 
            ORDER BY r.created_at DESC
        ");
        $review_stmt->execute([$service_id]);
        $reviews = $review_stmt->fetchAll();
        ?>

        <?php if (count($reviews) === 0): ?>
            <p>Nenhuma avaliação ainda.</p>
        <?php endif; ?>

        <?php if ($totalReviews > 0): ?>
            <div class="average-rating">
                <div class="stars">
                <?php
                    $filled = floor($averageRating);
                    $half = ($averageRating - $filled >= 0.5) ? 1 : 0;
                    $empty = 5 - $filled - $half;

                    for ($i = 0; $i < $filled; $i++) echo '<span class="star full">★</span>';
                    if ($half) echo '<span class="star half">★</span>';
                    for ($i = 0; $i < $empty; $i++) echo '<span class="star empty">★</span>';
                ?>
                </div>
                <p><?= $averageRating ?> em 5 (<?= $totalReviews ?> avaliação<?= $totalReviews > 1 ? 'es' : '' ?>)</p>
            </div>
        <?php endif; ?>

        <?php foreach ($reviews as $review): ?>
            <div class="review">
                <strong><?= htmlspecialchars($review['username']) ?></strong> <?= $review['rating'] ?>/5
                <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
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

    <!-- Encomendar -->
    <div id="order-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        
        <section class="order-container">
        <h2>Encomendar Serviço</h2>

        <div class="service-summary">
            <p><strong>Serviço:</strong> <?= htmlspecialchars($service['title']) ?></p>
            <p><strong>Prestador:</strong> <?= htmlspecialchars($service['username']) ?></p>
            <p><strong>Preço:</strong> €<?= number_format($service['price'], 2, ',', '.') ?></p>
            <p><strong>Tempo de entrega:</strong> <?= $service['delivery_time'] ?> dias</p>
        </div>

        <form action="../actions/create_order.php" method="POST">
            <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
            <input type="hidden" name="freelancer_id" value="<?= $service['freelancer_id'] ?>">

            <?php if($order_id): ?>
            <input type="hidden" name="order_id" value="<?= $order_id ?>">
            <?php endif; ?>

            <label for="details">Detalhes da Encomenda:</label>
            <textarea
            name="details"
            id="details"
            rows="5"
            placeholder="Descreve claramente o que pretendes com este serviço..."
            required
            ></textarea>

            <button type="submit" class="btn">Confirmar</button>
        </form>
        </section>
    </div>
    </div>

</div>

<script src="../js/order.js"></script>
<script src="../js/messages.js"></script>

<?php drawFooter(); ?>
