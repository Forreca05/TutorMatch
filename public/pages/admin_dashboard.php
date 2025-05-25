<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once '../../private/database/db.php';

// Contadores
$totalUsers = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalAdmins = $db->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
$totalServices = $db->query("SELECT COUNT(*) FROM services")->fetchColumn();
$totalOrders = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();

// Últimos utilizadores
$latestUsers = $db->query("SELECT username, email, role FROM users ORDER BY id DESC LIMIT 5")->fetchAll();
?>

<?php require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader(); ?>

<h1>Painel de Administração</h1>
<div class="admin-dashboard-stats">
    <div class="admin-stat-card">Utilizadores: <?php echo $totalUsers; ?></div>
    <div class="admin-stat-card">Admins: <?php echo $totalAdmins; ?></div>
    <div class="admin-stat-card">Serviços: <?php echo $totalServices; ?></div>
    <div class="admin-stat-card">Pedidos: <?php echo $totalOrders; ?></div>
</div>

<div class="admin-dashboard-actions">
    <a href="manage_users.php" class="admin-action-btn">Gerir Utilizadores</a>
    <a href="manage_categories.php" class="admin-action-btn">Gerir Categorias</a>
    <a href="available_services.php" class="admin-action-btn">Ver Serviços</a>
</div>

<h2>Últimos Utilizadores Registados</h2>
<table class="admin-users-table">
    <tr><th>Username</th><th>Email</th><th>Role</th></tr>
    <?php foreach ($latestUsers as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['role']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
