<?php
session_start();
require_once '../../private/database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['action'])) {
    $order_id = intval($_POST['order_id']);
    $action = $_POST['action'];

    $valid_actions = ['accepted', 'rejected'];
    if (!in_array($action, $valid_actions)) {
        header("Location: ../pages/orders.php?error=invalid_action");
        exit;
    }

    $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$action, $order_id]);

    header("Location: ../pages/orders.php?update=success");
    exit;
} else {
    header("Location: ../pages/orders.php?error=invalid_request");
    exit;
}
