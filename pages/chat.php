<?php
session_start();
require_once '../database/db.php';

$order_id = intval($_GET['order_id'] ?? 0);

$stmt = $db->prepare("SELECT m.*, u.username FROM messages m JOIN users u ON m.sender_id = u.id WHERE order_id = ? ORDER BY m.created_at ASC");
$stmt->execute([$order_id]);
$messages = $stmt->fetchAll();
?>

<?php include_once '../includes/header.php'; ?>
<h2>Mensagens</h2>
<div class="chat-box">
  <?php foreach ($messages as $msg): ?>
    <div class="message">
      <strong><?= htmlspecialchars($msg['username']) ?>:</strong> <?= htmlspecialchars($msg['message']) ?>
    </div>
  <?php endforeach; ?>
</div>

<form action="../actions/send_message.php" method="POST">
  <input type="hidden" name="order_id" value="<?= $order_id ?>">
  <textarea name="message" required></textarea>
  <button type="submit">Enviar</button>
</form>
<?php include_once '../includes/footer.php'; ?>
