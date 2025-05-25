<?php
session_start();
require_once '../../private/database/db.php';
require_once(__DIR__ . '/../../private/utils/csrf.php');
if (!isset($_SESSION['user_id'])) {
  die('Usuário não está logado');
}

$userId = $_SESSION['user_id'];

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if ($user) {
  $profilePic = $user['profile_pic'] ?: 'default.jpg';
} else {
  die('Usuário não encontrado.');
}
?>

<?php require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader(); ?>

<div class="container-md">
  <h2 class="text-center">Editar Perfil</h2>  
  <form action="../actions/action_updateprofile.php" method="POST" enctype="multipart/form-data" class="form">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    <div class="form-row">
      <div class="text-center">
        <?php drawProfilePicture("../uploads/" . $profilePic, 'Foto de Perfil'); ?>
        <label for="profile_pic" class="form-label mt">Nova Foto de Perfil</label>
        <input type="file" name="profile_pic" id="profile_pic" accept=".jpg,.jpeg,.png" class="form-input">
      </div>

    </div>

    <div class="form-group">
      <label for="username" class="form-label">Nome de Utilizador</label>
      <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="form-input">
    </div>

    <div class="form-group">
      <label for="name" class="form-label">Nome Completo</label>
      <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" class="form-input">
    </div>

    <div class="form-group">
      <label for="email" class="form-label">Email</label>
      <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" class="form-input">
    </div>

    <button type="submit" class="btn btn-full">Guardar Alterações</button>
  </form>
</div>

<?php drawFooter(); ?>