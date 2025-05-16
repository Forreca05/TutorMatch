<?php
session_start();
require_once '../database/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') die('Acesso negado.');

$user_id = $_SESSION['user_id'];

// Total de serviços publicados
$total_services_stmt = $db->prepare("SELECT COUNT(*) FROM services WHERE user_id = ?");
$total_services_stmt->execute([$user_id]);
$total_services = $total_services_stmt->fetchColumn();

// Total de encomendas recebidas
$total_orders_stmt = $db->prepare("
    SELECT COUNT(*) FROM orders o
    JOIN services s ON s.id = o.service_id
    WHERE s.user_id = ?
");
$total_orders_stmt->execute([$user_id]);
$total_orders = $total_orders_stmt->fetchColumn();

// Ganhos totais (apenas concluídas)
$total_earnings_stmt = $db->prepare("
    SELECT SUM(s.price) FROM orders o
    JOIN services s ON s.id = o.service_id
    WHERE s.user_id = ? AND o.status = 'concluído'
");
$total_earnings_stmt->execute([$user_id]);
$total_earnings = $total_earnings_stmt->fetchColumn();
$total_earnings = $total_earnings !== null ? $total_earnings : 0;

include '../includes/header.php';
?>
<link rel="stylesheet" href="/css/freelancer_dashboard.css">

<div class="freelancer-dashboard">
  <h2>Painel do Freelancer</h2>

  <div class="dashboard-cards">
    <div class="card">
      <h3>Serviços Publicados</h3>
      <p><?= $total_services ?></p>
      <a href="/pages/my_services.php" class="hint-link">Ver lista »</a>
    </div>

    <div class="card">
      <h3>Encomendas Recebidas</h3>
      <p><?= $total_orders ?></p>
      <a href="/pages/orders.php" class="hint-link">Ver encomendas »</a>
    </div>

    <div class="card">
      <h3>Ganhos Totais</h3>
      <p>€<?= number_format($total_earnings, 2) ?></p>
      <a href="/pages/earnings.php" class="hint-link">Detalhe »</a>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
