<?php
session_start();
require_once '../database/db.php';

$order_id = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);
$user_id = $_SESSION['user_id'] ?? null;

if (!$order_id || !$user_id) {
    die('Acesso negado.');
}

// Obter mensagens
$stmt = $db->prepare("
    SELECT m.*, u.username 
    FROM messages m 
    JOIN users u ON m.sender_id = u.id 
    WHERE m.order_id = ?
    ORDER BY m.created_at ASC
");
$stmt->execute([$order_id]);
$messages = $stmt->fetchAll();

// Processar envio
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $msg = trim($_POST['message'] ?? '');
    if (!empty($msg)) {
        $stmt = $db->prepare("INSERT INTO messages (order_id, sender_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$order_id, $user_id, $msg]);
        header("Location: chat.php?order_id=$order_id");
        exit;
    }
}
?>

<?php include_once '../includes/header.php'; ?>
<link rel="stylesheet" href="../css/chat.css">

<div class="chat-container">
    <div class="chat-header">
        <h2>Chat do Pedido #<?= $order_id ?></h2>
    </div>

    <div class="chat-box">
        <?php
        $currentDate = null;
        foreach ($messages as $msg):
            $msgDate = date('Y-m-d', strtotime($msg['created_at']));
            if ($msgDate !== $currentDate):
                $currentDate = $msgDate;
                $today = date('Y-m-d');
                $yesterday = date('Y-m-d', strtotime('-1 day'));
                $label = ($msgDate == $today) ? 'Hoje' : (($msgDate == $yesterday) ? 'Ontem' : date('d M Y', strtotime($msgDate)));
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

    <form action="chat.php?order_id=<?= $order_id ?>" method="POST" class="chat-form">
        <input type="text" name="message" placeholder="Escreve uma mensagem..." required>
        <button type="submit">Enviar</button>
    </form>
</div>

<?php include_once '../includes/footer.php'; ?>
