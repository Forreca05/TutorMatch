<?php
session_start(); // Inicia a sessão
require_once '../database/db.php'; // Inclui a configuração do banco de dados

if (!isset($_SESSION['user_id'])) {
    die('Usuário não está logado');
}

$userId = $_SESSION['user_id']; // Obtém o ID do usuário da sessão

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

?>

<?php include_once '../includes/header.php'; ?>

<h2>Editar Perfil</h2>

<form action="../actions/action_uploadphoto.php" method="POST" enctype="multipart/form-data">
  <label for="profile_pic">Escolher nova foto de perfil:</label><br>
  <input type="file" name="profile_pic" id="profile_pic" accept="image/*"><br><br>

  <label for="username">Nome de Usuário:</label><br>
  <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" <br><br>

  <label for="name">Nome Completo:</label><br>
  <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>"><br><br>

  <button type="submit">Atualizar Foto</button>
</form>

<?php include_once '../includes/footer.php'; ?>
