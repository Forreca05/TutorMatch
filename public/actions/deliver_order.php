<?php
session_start();
require_once '../../private/database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $order_id = intval($_POST['order_id']);

  $stmt = $db->prepare("UPDATE orders SET status = 'Entregue' WHERE id = ?");
  $stmt->execute([$order_id]);

  header("Location: ../pages/orders.php");
  exit;
} else {
  header("Location: ../pages/orders.php?error=invalid_request");
  exit;
}
