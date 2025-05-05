<?php
session_start();
require_once '../database/db.php';

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

<?php include_once '../includes/header.php'; ?>

<div class="profile-container">
  <div class="profile-sidebar">
    <img src="../uploads/<?php echo htmlspecialchars($profilePic); ?>" alt="Foto de Perfil" class="profile-avatar">
  </div>
  <div class="profile-main">
    <h2><?php echo htmlspecialchars($user['name']); ?> <small>(<?php echo htmlspecialchars($user['username']); ?>)</small></h2>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Função:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
    <p><strong>Data de Registo:</strong> <?php echo htmlspecialchars(date('d/m/Y', strtotime($user['created_at']))); ?></p>

    <a href="../pages/edit_profile.php" class="btn">Editar Perfil</a>
    <a href="../pages/change_password.php" class="btn">Mudar Password</a>
  </div>
</div>

<?php include_once '../includes/footer.php'; ?>
