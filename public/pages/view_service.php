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

<div class="container">
  <?php drawPageHeader($service['title'], 'Detalhes do serviço'); ?>
  
  <div class="service-details bg-secondary rounded p-lg mb-lg">
    <div class="mb">
      <span class="text-muted">Categoria:</span> 
      <span class="text-primary font-bold"><?= htmlspecialchars($service['category_name']) ?></span>
    </div>
    
    <div class="mb">
      <span class="text-muted">Prestador:</span> 
      <a href="/pages/view_profile.php?id=<?= $service['freelancer_id'] ?>" class="text-primary font-bold">
        <?= htmlspecialchars($service['username']) ?>
      </a>
    </div>
    
    <div class="mb-lg">
      <p class="text-lg"><?= nl2br(htmlspecialchars($service['description'])) ?></p>
    </div>
    
    <div class="d-flex justify-between align-center mb">
      <div>
        <span class="text-lg font-bold text-primary">€<?= number_format($service['price'], 2, ',', '.') ?></span>
      </div>
      <div class="text-sm text-muted">
        Entrega em <?= htmlspecialchars($service['delivery_time']) ?> dias
      </div>
    </div>

    <?php if (!empty($service['image_path'])): ?>
      <div class="mb-lg text-center">
        <img src="<?= htmlspecialchars($service['image_path']) ?>" 
             alt="Imagem do serviço" 
             class="w-full rounded shadow" 
             style="max-height: 400px; object-fit: cover;">
      </div>
    <?php endif; ?>

    <div class="d-flex gap justify-center mt-lg">
      <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'client'): ?>
        <button id="order-link" class="btn btn-primary">Encomendar Serviço</button>
      <?php endif; ?>

      <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $service['freelancer_id']): ?>
        <a href="../pages/edit_service.php?id=<?= $service_id ?>" class="btn btn-success">Editar Serviço</a>
        <a href="../actions/delete_service.php?id=<?= $service_id ?>" 
           class="btn btn-danger" 
           onclick="return confirm('Tem certeza que deseja apagar este serviço?')">Apagar Serviço</a>
      <?php endif; ?>
    </div>
  </div>

  <!-- Reviews Section -->
  <div class="reviews-section bg-secondary rounded p-lg mt-lg">
    <h3 class="mb-lg text-center">Avaliações</h3>

    <?php if (isset($_GET['review']) && $_GET['review'] === 'success'): ?>
      <div class="message message-success">Avaliação submetida com sucesso!</div>
    <?php elseif (isset($_GET['review']) && $_GET['review'] === 'error'): ?>
      <div class="message message-error">Erro ao submeter avaliação.</div>
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

    <?php if ($totalReviews > 0): ?>
      <div class="mb-lg text-center">
        <div class="stars mb-sm">
          <?php
          $filled = floor($averageRating);
          $half = ($averageRating - $filled >= 0.5) ? 1 : 0;
          $empty = 5 - $filled - $half;

          for ($i = 0; $i < $filled; $i++) echo '<span class="text-primary">★</span>';
          if ($half) echo '<span class="text-primary">★</span>';
          for ($i = 0; $i < $empty; $i++) echo '<span class="text-muted">★</span>';
          ?>
        </div>
        <p class="text-lg font-bold"><?= $averageRating ?> em 5 <span class="text-muted">(<?= $totalReviews ?> avaliação<?= $totalReviews > 1 ? 'es' : '' ?>)</span></p>
      </div>
    <?php endif; ?>

    <?php if (empty($reviews)): ?>
      <?php drawEmptyState('Nenhuma avaliação ainda.', 'Seja o primeiro a avaliar'); ?>
    <?php else: ?>
      <div class="reviews-list">
        <?php foreach ($reviews as $review): ?>
          <div class="review-item bg-tertiary rounded p mb">
            <div class="d-flex justify-between align-center mb-sm">
              <strong class="text-primary"><?= htmlspecialchars($review['username']) ?></strong>
              <span class="text-lg font-bold"><?= $review['rating'] ?>/5 ⭐</span>
            </div>
            <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'client'): ?>
      <div class="review-form-section bg-tertiary rounded p-lg mt-lg">
        <h4 class="mb">Deixe uma avaliação</h4>
        <form action="../actions/submit_review.php" method="POST" class="form">
          <input type="hidden" name="service_id" value="<?= $service_id ?>">
          
          <?php 
          $ratingOptions = '<option value="">Escolha...</option>';
          for ($i = 1; $i <= 5; $i++) {
            $ratingOptions .= '<option value="' . $i . '">' . $i . ' estrela' . ($i > 1 ? 's' : '') . '</option>';
          }
          drawFormField('select', 'rating', 'Classificação', $ratingOptions, [], true);
          drawFormField('textarea', 'comment', 'Comentário', '', ['rows' => '4', 'placeholder' => 'Partilhe a sua experiência...'], true);
          ?>
          
          <button type="submit" class="btn btn-primary">Submeter Avaliação</button>
        </form>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Modal para Encomendar -->
<div id="order-modal" class="modal">
  <div class="modal-content">
    <span class="modal-close">&times;</span>

    <div class="order-container">
      <?php drawPageHeader('Encomendar Serviço', 'Confirme os detalhes da sua encomenda'); ?>

      <div class="service-summary bg-tertiary rounded p mb-lg">
        <h4 class="mb">Resumo do Serviço</h4>
        <p><strong>Serviço:</strong> <?= htmlspecialchars($service['title']) ?></p>
        <p><strong>Prestador:</strong> <?= htmlspecialchars($service['username']) ?></p>
        <p><strong>Preço:</strong> €<?= number_format($service['price'], 2, ',', '.') ?></p>
        <p><strong>Tempo de entrega:</strong> <?= $service['delivery_time'] ?> dias</p>
      </div>

      <form action="../actions/create_order.php" method="POST" class="form">
        <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
        <input type="hidden" name="freelancer_id" value="<?= $service['freelancer_id'] ?>">

        <?php if ($order_id): ?>
          <input type="hidden" name="order_id" value="<?= $order_id ?>">
        <?php endif; ?>

        <?php drawFormField('textarea', 'details', 'Detalhes da Encomenda', '', 
          ['rows' => '5', 'placeholder' => 'Descreve claramente o que pretendes com este serviço...'], true); ?>

        <button type="submit" class="btn btn-primary btn-large w-full">Confirmar Encomenda</button>
      </form>
    </div>
  </div>
</div>

</div>

<script src="../js/order.js"></script>
<script src="../js/messages.js"></script>

<?php drawFooter(); ?>