<?php
session_start();
require_once '../../private/database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    $action = $_POST['action'];

    if ($action === 'Aceite') {
        $stmt = $db->prepare("UPDATE orders SET status = 'Concluído' WHERE id = ?");
        $stmt->execute([$order_id]);
    } elseif ($action === 'Rejeitado') {
        $stmt = $db->prepare("UPDATE orders SET status = 'Pago' WHERE id = ?");
        $stmt->execute([$order_id]);
    }

    header("Location: ../pages/orders.php");
    exit;
} else {
    header("Location: ../pages/orders.php?error=invalid_request");
    exit;
}
?>