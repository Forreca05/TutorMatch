<?php 
session_start(); // Inicia a sessão
require_once '../database/db.php'; // Inclui a configuração do banco de dados
include_once '../includes/header.php'; 

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($user) {
    $profilePic = $user['profile_pic'] ?: 'default.jpg';
} else {
    echo 'Usuário não encontrado.';
    exit;
}
?>

<div class="profile-container">
  <div class="profile-sidebar">
    <img src="../uploads/<?php echo htmlspecialchars($profilePic); ?>" alt="Foto de Perfil" class="profile-avatar">
  </div>
  <div class="profile-main">
    <h2><?php echo htmlspecialchars($_SESSION['username']); ?></h2>
    <a href="../pages/edit_profile.php" class="btn">Editar Perfil</a>
  </div>
</div>

<?php include_once '../includes/footer.php'; ?>
