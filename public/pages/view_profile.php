<?php
require_once '../../private/database/db.php';
session_start();

$currentUserId = $_SESSION['user_id'] ?? null;
$currentUserRole = $_SESSION['role'] ?? null;

$viewedUserId = $_GET['id'] ?? null;

if (!$viewedUserId) {
  die('Utilizador não especificado.');
}

$stmt = $db->prepare("SELECT id, username, profile_pic, role FROM users WHERE id = ?");
$stmt->execute([$viewedUserId]);
$user = $stmt->fetch();

if (!$user) {
  die('Utilizador não encontrado.');
}

$isOwnProfile = $currentUserId && $currentUserId == $user['id'];
$isAdmin = $currentUserRole === 'admin';

$canViewAll = $isOwnProfile || $isAdmin;

$profilePic = $user['profile_pic'] ?? '/img/default.jpeg';
$username = $user['username'];
$role = $user['role'];
?>

<?php require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader(); ?>
<link rel="stylesheet" href="/css/view_profile.css">

<div class="user-profile-container">
  <?php drawProfilePicture($profilePic, 'Foto de perfil', '150px'); ?>
  <h2 class="user-profile-name"><?= htmlspecialchars($username) ?></h2>

  <?php if (htmlspecialchars($role) === 'admin'): ?>
    <p class="user-profile-role admin">Tipo de utilizador: admin</p>
  <?php else: ?>
    <p class="user-profile-role">Tipo de utilizador: client/freelancer</p>
  <?php endif; ?>

  <a href="/pages/user_services.php?id=<?= urlencode($user['id']) ?>" class="user-profile-btn">
    Ver Serviços que Oferece
  </a>

  <a href="/pages/user_orders.php?id=<?= urlencode($user['id']) ?>" class="user-profile-btn">
    Ver Serviços que Comprou
  </a>
</div>


<?php drawFooter(); ?>