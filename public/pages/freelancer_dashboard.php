<?php
session_start();
require_once '../../private/database/db.php';

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
  WHERE s.user_id = ? AND o.status = 'Concluído'
");
$total_earnings_stmt->execute([$user_id]);
$total_earnings = $total_earnings_stmt->fetchColumn();
$total_earnings = $total_earnings !== null ? $total_earnings : 0;

require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader();
?>

<div class="container">
  <div class="page-header">
    <h2 class="text-center">Painel do Freelancer</h2>
  </div>

  <div class="card-list">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Serviços Publicados</h3>
      </div>
      <div class="card-body">
        <p class="text-lg font-bold text-primary text-center"><?= $total_services ?></p>
        <a href="/pages/my_services.php" class="btn btn-primary">Ver lista »</a>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Encomendas Recebidas</h3>
      </div>
      <div class="card-body">
        <p class="text-lg font-bold text-primary text-center"><?= $total_orders ?></p>
        <a href="/pages/orders.php" class="btn btn-primary">Ver encomendas »</a>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Ganhos Totais</h3>
      </div>
      <div class="card-body">
        <p class="text-lg font-bold text-success text-center">€<?= number_format($total_earnings, 2) ?></p>
      </div>
    </div>
  </div>
</div>

<?php drawFooter(); ?>