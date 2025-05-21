<?php
session_start();
require_once '../../private/database/db.php';

// Garantir que o utilizador está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Verifica se o método é POST e o ID do serviço foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['service_id'])) {
    $service_id = intval($_POST['service_id']);
    $client_id = $_SESSION['user_id'];

    // Obter dados do serviço
    $stmt = $db->prepare("SELECT s.id, s.user_id AS freelancer_id, s.price FROM services s WHERE s.id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        header("Location: ../pages/services.php?error=servico_invalido");
        exit;
    }

    // Impedir que o próprio freelancer encomende o serviço
    if ($service['freelancer_id'] == $client_id) {
        header("Location: ../pages/view_service.php?id=$service_id&error=proprio_servico");
        exit;
    }

    // Criar o pedido
    $stmt = $db->prepare("INSERT INTO orders (service_id, client_id, freelancer_id, status, created_at) VALUES (?, ?, ?, 'pending', datetime('now'))");
    $stmt->execute([$service_id, $client_id, $service['freelancer_id']]);

    // Redirecionar com sucesso
    header("Location: ../pages/view_service.php?id=$service_id&order=success");
    exit;
}

// Redireciona se algo estiver errado
header("Location: ../pages/services.php?error=pedido_falhado");
exit;
?>
