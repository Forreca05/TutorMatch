<?php
session_start();
require_once '../database/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Serviço não especificado.");
}

$service_id = intval($_GET['id']);

// Get service details
$stmt = $db->prepare(
    "SELECT s.*, u.username AS freelancer_name, u.id AS freelancer_id FROM services s JOIN users u ON s.user_id = u.id WHERE s.id = ?"
);
$stmt->execute([$service_id]);
$service = $stmt->fetch();

if (!$service) {
    die("Serviço não encontrado.");
}
?>

<?php include_once '../includes/header.php'; ?>
<link rel="stylesheet" href="../css/order_service.css">

<div class="order-container">
    <h2>Encomendar: <?= htmlspecialchars($service['title']) ?></h2>

    <p><strong>Prestador:</strong> <?= htmlspecialchars($service['freelancer_name']) ?></p>
    <p><strong>Preço:</strong> €<?= number_format($service['price'], 2) ?></p>
    <p><strong>Entrega:</strong> <?= $service['delivery_time'] ?> dias</p>

    <form action="../actions/create_order.php" method="POST">
        <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
        <input type="hidden" name="freelancer_id" value="<?= $service['freelancer_id'] ?>">

        <label for="details">Detalhes da Encomenda:</label>
        <textarea name="details" id="details" rows="5" required></textarea>

        <h3>Dados de Pagamento</h3>
        <label for="card_name">Nome no Cartão:</label>
        <input type="text" id="card_name" name="card_name" placeholder="Nome impresso no cartão" required>

        <label for="card_number">Número do Cartão:</label>
        <input type="text" id="card_number" name="card_number" placeholder="0000 0000 0000 0000" maxlength="19" required>

        <div class="card-row">
            <div class="card-col">
                <label for="expiry">Validade (MM/AA):</label>
                <input type="text" id="expiry" name="expiry" placeholder="MM/AA" maxlength="5" required>
            </div>
            <div class="card-col">
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4" required>
            </div>
        </div>

        <button type="submit" class="btn">Confirmar e Pagar</button>
    </form>
</div>

<?php include_once '../includes/footer.php'; ?>
