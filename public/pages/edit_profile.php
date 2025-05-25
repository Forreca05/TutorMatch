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

if ($user) {
    $profilePic = $user['profile_pic'] ?: 'default.jpg';
} else {
    die('Usuário não encontrado.');
}
?>

<?php require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader(); ?>

<div class="profile-container">
    <form action="../actions/action_updateprofile.php" method="POST" enctype="multipart/form-data" class="profile-form">
        <div class="profile-image-section">
            <img src="../uploads/<?php echo htmlspecialchars($profilePic); ?>" alt="Foto de Perfil" class="profile-avatar">
            <label for="profile_pic" class="profile-label">Nova Foto de Perfil</label>
            <input type="file" name="profile_pic" id="profile_pic" accept=".jpg,.jpeg,.png">
        </div>

        <div class="profile-fields">
            <label for="username" class="profile-label">Nome de Utilizador</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>">

            <label for="name" class="profile-label">Nome Completo</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>">
            
            <label for="email" class="profile-label">Email</label>
            <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">

            <button type="submit" class="btn">Guardar Alterações</button>
        </div>
    </form>
</div>

<?php drawFooter(); ?>
