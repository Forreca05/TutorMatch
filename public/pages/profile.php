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

<div class="container-sm">
  <?php drawPageHeader('Meu Perfil', 'Gerir as suas informações pessoais'); ?>

  <div class="card-body text-center">
    <?php drawProfilePicture("../uploads/" . $profilePic, 'Foto de Perfil', '120px'); ?>

    <h3 class="mt"><?php echo htmlspecialchars($user['name'] ?? 'Ainda não temos o teu nome completo'); ?></h3>
    <p class="text-muted">@<?php echo htmlspecialchars($user['username'] ?? 'username'); ?></p>

    <div class="mt-lg">
      <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
      <p><strong>Função:</strong> <span class="text-primary"><?php echo htmlspecialchars($user['role']); ?></span></p>
    </div>

    <div class="mt-lg">
      <a href="../pages/edit_profile.php" class="btn btn-primary">Editar Perfil</a>
      <a href="../pages/change_password.php" class="btn btn-secondary">Mudar Password</a>
    </div>
  </div>
</div>

<?php drawFooter(); ?>