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

<div class="container-sm">
  <?php drawPageHeader($username, 'Perfil do utilizador'); ?>
  
  <div class="text-center">
    <div class="card-body">
      <?php drawProfilePicture($profilePic, 'Foto de perfil', '150px'); ?>
      <h2 class="mt"><?= htmlspecialchars($username) ?></h2>
      
      <?php if (htmlspecialchars($role) === 'admin'): ?>
        <p class="text-primary font-bold">Tipo de utilizador: admin</p>
      <?php else: ?>
        <p class="text-muted">Tipo de utilizador: client/freelancer</p>
      <?php endif; ?>
      
      <div class="mt-lg">
        <a href="/pages/user_services.php?id=<?= urlencode($user['id']) ?>" class="btn btn-primary">
          Ver Serviços que Oferece
        </a>
        <a href="/pages/user_orders.php?id=<?= urlencode($user['id']) ?>" class="btn btn-secondary">
          Ver Serviços que Comprou
        </a>
      </div>
    </div>
  </div>
</div>

<?php drawFooter(); ?>