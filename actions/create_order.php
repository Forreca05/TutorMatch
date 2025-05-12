<?php
// File: actions/create_order.php
session_start();
require_once '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = intval($_POST['service_id']);
    $client_id = $_SESSION['user_id'] ?? 0;

    // Get freelancer from the service
    $stmt = $db->prepare("SELECT user_id FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    $freelancer_id = $stmt->fetchColumn();

    if ($freelancer_id && $client_id) {
        $stmt = $db->prepare("INSERT INTO orders (service_id, client_id, freelancer_id, status, created_at) VALUES (?, ?, ?, 'pending', CURRENT_TIMESTAMP)");
        $stmt->execute([$service_id, $client_id, $freelancer_id]);
        header("Location: ../pages/view_service.php?id=$service_id&order=success");
        exit;
    }
}
header("Location: ../pages/services.php?error=order_failed");
exit;
