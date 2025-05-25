<?php
session_start();
require_once '../../private/database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $order_id = intval($_POST['order_id']);
  $sender_id = $_SESSION['user_id'];
  $content = trim($_POST['message']);

  if (!empty($content)) {
    $stmt = $db->prepare("INSERT INTO messages (order_id, sender_id, message, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)");
    $stmt->execute([$order_id, $sender_id, $content]);
  }
  header("Location: ../pages/chat.php?order_id=$order_id");
  exit;
}
