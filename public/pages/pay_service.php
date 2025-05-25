<?php
session_start();
require_once '../../private/database/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
  header("Location: ../auth/login.php");
  exit;
}

$service_id = intval($_GET['id']);
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : null;

// Obter detalhes do serviço
$stmt = $db->prepare(
  "SELECT s.*, u.username AS freelancer_name, u.id AS freelancer_id 
    FROM services s 
    JOIN users u ON s.user_id = u.id 
    WHERE s.id = ?"
);
$stmt->execute([$service_id]);
$service = $stmt->fetch();

if (!$service) {
  die("Serviço não encontrado.");
}
?>

<?php require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader(); ?>
<link rel="stylesheet" href="../css/order_service.css">

<section class="order-container">
  <h2>Proceder para Pagamento</h2>

  <div class="service-summary">
    <p><strong>Serviço:</strong> <?= htmlspecialchars($service['title']) ?></p>
    <p><strong>Prestador:</strong> <?= htmlspecialchars($service['freelancer_name']) ?></p>
    <p><strong>Preço:</strong> <span id="preco" data-eur="<?= $service['price'] ?>"><?= number_format($service['price'], 2, '.', ',') ?></span></p>
    <p><strong>Tempo de entrega:</strong> <?= $service['delivery_time'] ?> dias</p>
    <label for="currency"><strong>Moeda:</strong></label>
    <select id="currency" name="currency">
      <option value="EUR" selected>EUR</option>
      <option value="USD">USD</option>
      <option value="GBP">GBP</option>
      <option value="JPY">JPY</option>
      <option value="AUD">AUD</option>
    </select>
  </div>

  <form action="../actions/process_payment.php" method="POST">
    <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
    <input type="hidden" name="freelancer_id" value="<?= $service['freelancer_id'] ?>">
    <?php if ($order_id): ?>
      <input type="hidden" name="order_id" value="<?= $order_id ?>">
    <?php endif; ?>

    <h3>Informações de Pagamento</h3>

    <label for="card_name">Nome no Cartão:</label>
    <input type="text" id="card_name" name="card_name" placeholder="Nome completo" required>

    <label for="card_number">Número do Cartão:</label>
    <input type="text" id="card_number" name="card_number" placeholder="0000 0000 0000 0000" maxlength="19" required>

    <div class="card-flex">
      <div>
        <label for="expiry">Validade (MM/AA):</label>
        <input type="text" id="expiry" name="expiry" placeholder="MM/AA" maxlength="5" required>
      </div>
      <div>
        <label for="cvv">CVV:</label>
        <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4" required>
      </div>
    </div>

    <button type="submit" class="btn">Confirmar e Pagar</button>
  </form>
</section>

<script src="../js/currency.js"></script>

<?php drawFooter(); ?>