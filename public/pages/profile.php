<?php
session_start();
require_once '../../private/database/db.php';

if (!isset($_SESSION['user_id'])) {
  die('Usuário não está logado.');
}

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($user) {
  $profilePic = $user['profile_pic'] ?: 'default.jpg';
} else {
  die('Usuário não encontrado.');
}
?>

<?php require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader(); ?>

<div class="profile-container">
  <div class="profile-sidebar">
    <?php drawProfilePicture("../uploads/" . $profilePic, 'Foto de Perfil'); ?>
  </div>
  <div class="profile-main">
    <h3><?php echo htmlspecialchars($user['name'] ?? 'Ainda não temos o teu nome completo'); ?> <small>(<?php echo htmlspecialchars($user['username'] ?? 'Coloca aqui o teu nome'); ?>)</small></h3>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Função:</strong> <?php echo htmlspecialchars($user['role']); ?></p>

    <a href="../pages/edit_profile.php" class="btn">Editar Perfil</a>
    <a href="../pages/change_password.php" class="btn">Mudar Password</a>
  </div>
</div>

<?php drawFooter(); ?>