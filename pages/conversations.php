<?php
session_start();
require_once '../database/db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) die('Acesso negado.');

// Vai buscar todas as conversas do user (único par sender/receiver)
$stmt = $db->prepare("
  SELECT m1.*, u.username, u.profile_pic
  FROM messages m1
  JOIN users u ON u.id = CASE 
      WHEN m1.sender_id = :user_id THEN m1.receiver_id
      ELSE m1.sender_id
  END
  WHERE m1.id IN (
    SELECT MAX(id)
    FROM messages
    WHERE sender_id = :user_id OR receiver_id = :user_id
    GROUP BY 
      CASE 
        WHEN sender_id < receiver_id THEN sender_id
        ELSE receiver_id
      END,
      CASE 
        WHEN sender_id > receiver_id THEN sender_id
        ELSE receiver_id
      END
  )
  ORDER BY m1.created_at DESC
");


$stmt->execute(['user_id' => $user_id]);
$conversations = $stmt->fetchAll();
?>

<?php include_once '../includes/header.php'; ?>
<link rel="stylesheet" href="../css/chat.css">

<div class="chat-container">
  <div class="chat-header"><h2>As Minhas Conversas</h2></div>
  <div class="chat-box">
    <?php foreach ($conversations as $conv): 
      $other_id = ($conv['sender_id'] == $user_id) ? $conv['receiver_id'] : $conv['sender_id'];
    ?>
      <a class="chat-preview" href="chat.php?receiver_id=<?= $other_id ?>">
        <img src="<?= htmlspecialchars($conv['profile_pic'] ?? '/img/default.jpeg') ?>" class="chat-user-pic" alt="Foto">
        <div class="preview-text">
          <strong><?= htmlspecialchars($conv['username']) ?></strong><br>
          <?php if (strlen($conv['message']) > 40) {
                $previewMessage = substr($conv['message'], 0, 40) . '...';
              } else {
                $previewMessage = $conv['message'];
              }
          ?>
          <small><?= htmlspecialchars($previewMessage) ?></small>
        </div>
        <span class="preview-time"><?= date('H:i', strtotime($conv['created_at'])) ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<?php include_once '../includes/footer.php'; ?>
