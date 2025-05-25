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

<div class="container">
  <?php drawPageHeader('Proceder para Pagamento', 'Confirme os detalhes e efetue o pagamento'); ?>

  <div class="service-summary bg-secondary rounded p-lg mb-lg">
    <h3 class="mb">Resumo do Serviço</h3>
    <p class="mb-sm"><strong>Serviço:</strong> <?= htmlspecialchars($service['title']) ?></p>
    <p class="mb-sm"><strong>Prestador:</strong> <?= htmlspecialchars($service['freelancer_name']) ?></p>
    <p class="mb-sm"><strong>Preço:</strong> <span id="preco" data-eur="<?= $service['price'] ?>"><?= number_format($service['price'], 2, '.', ',') ?></span></p>
    <p class="mb-sm"><strong>Tempo de entrega:</strong> <?= $service['delivery_time'] ?> dias</p>
    
    <div class="form-field">
      <label for="currency" class="form-label"><strong>Moeda:</strong></label>
      <select id="currency" name="currency" class="form-select">
        <option value="EUR" selected>EUR</option>
        <option value="USD">USD</option>
        <option value="GBP">GBP</option>
        <option value="JPY">JPY</option>
        <option value="AUD">AUD</option>
      </select>
    </div>
  </div>

  <div class="payment-form bg-secondary rounded p-lg">
    <h3 class="mb">Informações de Pagamento</h3>

    <form action="../actions/process_payment.php" method="POST" class="form">
      <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
      <input type="hidden" name="freelancer_id" value="<?= $service['freelancer_id'] ?>">
      <?php if ($order_id): ?>
        <input type="hidden" name="order_id" value="<?= $order_id ?>">
      <?php endif; ?>

      <?php 
      drawFormField('text', 'card_name', 'Nome no Cartão', '', ['placeholder' => 'Nome completo'], true);
      drawFormField('text', 'card_number', 'Número do Cartão', '', ['placeholder' => '0000 0000 0000 0000', 'maxlength' => '19'], true);
      ?>

      <div class="form-row">
        <?php 
        drawFormField('text', 'expiry', 'Validade (MM/AA)', '', ['placeholder' => 'MM/AA', 'maxlength' => '5'], true);
        drawFormField('text', 'cvv', 'CVV', '', ['placeholder' => '123', 'maxlength' => '4'], true);
        ?>
      </div>

      <button type="submit" class="btn btn-primary btn-large w-full mt">Confirmar e Pagar</button>
    </form>
  </div>
</div>

<script src="../js/currency.js"></script>

<?php drawFooter(); ?>