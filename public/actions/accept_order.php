<?php
session_start();
require_once '../../private/database/db.php';
require_once(__DIR__ . '/../../private/utils/csrf.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&  verify_csrf_token($_POST['csrf_token'])) {
    $order_id = intval($_POST['order_id']);
    $action = $_POST['action'];

    if ($action === 'Aceite') {
        $stmt = $db->prepare("
            UPDATE orders SET status = 'Aceite' WHERE id = ?
        ");
        $stmt->execute([$order_id]);
    } elseif ($action === 'Rejeitado') {
        $stmt = $db->prepare("
            DELETE FROM orders WHERE id = ?
        ");
        $stmt->execute([$order_id]);
    }

    header('Location: ../pages/orders.php');
    exit;
}

header('Location: ../pages/orders.php?error=invalid_request');
exit;
