<?php
session_start();
require_once '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);

    $stmt = $db->prepare("UPDATE orders SET status = 'completed' WHERE id = ?");
    $stmt->execute([$order_id]);

    header("Location: ../pages/orders.php?complete=success");
    exit;
} else {
    header("Location: ../pages/orders.php?error=invalid_request");
    exit;
}
