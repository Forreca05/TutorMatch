<?php
session_start();
require_once '../../private/database/db.php'; // conexão à BD

// Garante que só admins acedem
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

// Buscar todos os utilizadores
$stmt = $db->query("SELECT id, username, email, role FROM users");
$users = $stmt->fetchAll();
?>

<?php require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader(); ?>

<h1>Gerir Utilizadores</h1>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Ações</th>
    </tr>

    <?php foreach ($users as $user): ?>
    <tr>
        <td><?php echo htmlspecialchars($user['id']); ?></td>
        <td><?php echo htmlspecialchars($user['username']); ?></td>
        <td><?php echo htmlspecialchars($user['email']); ?></td>
        <td><?php echo htmlspecialchars($user['role']); ?></td>
        <td>
            <?php if ($user['role'] !== 'admin'): ?>
                <a href="../actions/promote_user.php?id=<?php echo $user['id']; ?>">Promover a admin</a> |
            <?php endif; ?>
            <a href="../actions/delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Tem a certeza que quer eliminar?')">Eliminar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php drawFooter(); ?>
