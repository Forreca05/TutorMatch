<?php
session_start();
require_once '../../private/database/db.php';

if (!isset($_SESSION['user_id'])) {
    die('Usuário não está logado');
}

$userId = $_SESSION['user_id'];

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
?>

<?php include_once '../includes/header.php'; ?>

<h2>Editar Perfil</h2>

<form action="../actions/action_updateprofile.php" method="POST" enctype="multipart/form-data">
  <label for="profile_pic">Nova Foto de Perfil:</label><br>
  <input type="file" name="profile_pic" id="profile_pic" accept="image/*"><br><br>

  <label for="username">Nome de Utilizador:</label><br>
  <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>"><br><br>

  <label for="name">Nome Completo:</label><br>
  <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name'] ?? 'Your user name'); ?>"><br><br>

  <button type="submit">Guardar Alterações</button>
</form>

<?php include_once '../includes/footer.php'; ?>
