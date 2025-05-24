<?php
session_start();
require_once '../../private/database/db.php';

$user_id = $_SESSION['user_id'] ?? null;
$receiver_id = $_GET['receiver_id'] ?? null;

if (!$user_id || !$receiver_id) {
    die('Acesso negado.');
}

// Buscar info do utilizador com quem se está a falar
$stmt = $db->prepare("SELECT username, profile_pic FROM users WHERE id = ?");
$stmt->execute([$receiver_id]);
$chatUser = $stmt->fetch();

if (!$chatUser) {
    die('Utilizador não encontrado.');
}

$chatUserName = $chatUser['username'];
$chatUserPic = $chatUser['profile_pic'] ?? '/img/default.jpeg';

// Mensagens entre os dois
$stmt = $db->prepare("
    SELECT m.*, u.username 
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE 
        ((sender_id = :user1 AND receiver_id = :user2) 
        OR (sender_id = :user2 AND receiver_id = :user1))
        AND m.order_id IS NULL
    ORDER BY m.created_at ASC
");
$stmt->execute(['user1' => $user_id, 'user2' => $receiver_id]);
$messages = $stmt->fetchAll();

// Nova mensagem
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $msg = trim($_POST['message'] ?? '');
    if (!empty($msg)) {
        $stmt = $db->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $receiver_id, $msg]);
        header("Location: chat.php?receiver_id=$receiver_id");
        exit;
    }
}
?>

<?php require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader(); ?>
<link rel="stylesheet" href="../css/chat.css">

<!-- Extra estilo para remover sublinhado e ajustar link -->
<style>
.chat-header h2 a {
  text-decoration: none;
  color: inherit;
  cursor: pointer;
}
</style>

<div class="chat-container">
    <div class="chat-header">
        <img src="<?= htmlspecialchars($chatUserPic) ?>" alt="Foto de perfil" class="chat-user-pic">
        <h2>
            <a href="/pages/view_profile.php?id=<?= urlencode($receiver_id) ?>">
                <?= htmlspecialchars($chatUserName) ?>
            </a>
        </h2>
    </div>

    <div class="chat-box">
        <?php
        $currentDate = null;
        foreach ($messages as $msg):
            $msgDate = date('Y-m-d', strtotime($msg['created_at']));
            if ($msgDate !== $currentDate):
                $currentDate = $msgDate;
                $label = ($msgDate == date('Y-m-d')) ? 'Hoje' :
                         ($msgDate == date('Y-m-d', strtotime('-1 day')) ? 'Ontem' :
                         date('d M Y', strtotime($msgDate)));
        ?>
            <div class="date-separator"><?= $label ?></div>
        <?php endif; ?>
            <div class="chat-message <?= $msg['sender_id'] == $user_id ? 'sent' : 'received' ?>">
                <div class="bubble">
                    <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                    <span class="meta"><?= htmlspecialchars($msg['username']) ?> · <?= date('H:i', strtotime($msg['created_at'])) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <form action="chat.php?receiver_id=<?= $receiver_id ?>" method="POST" class="chat-form">
        <input type="text" name="message" placeholder="Escreve uma mensagem..." required>
        <button type="submit">Enviar</button>
    </form>
</div>

<?php drawFooter(); ?>
