<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once '../database/db.php';

// Contadores
$totalUsers = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalAdmins = $db->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
$totalServices = $db->query("SELECT COUNT(*) FROM services")->fetchColumn();
$totalOrders = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();

// Últimos utilizadores
$latestUsers = $db->query("SELECT username, email, role FROM users ORDER BY id DESC LIMIT 5")->fetchAll();
?>

<?php include_once '../includes/header.php'; ?>

<h1>Painel de Administração</h1>

<div class="dashboard-stats">
    <div class="stat">Utilizadores: <?php echo $totalUsers; ?></div>
    <div class="stat">Admins: <?php echo $totalAdmins; ?></div>
    <div class="stat">Serviços: <?php echo $totalServices; ?></div>
    <div class="stat">Pedidos: <?php echo $totalOrders; ?></div>
</div>

<div class="dashboard-actions">
    <a href="manage_users.php" class="btn">Gerir Utilizadores</a>
    <a href="manage_categories.php" class="btn">Gerir Categorias</a>
    <a href="available_services.php" class="btn">Ver Serviços</a>
    <a href="orders.php" class="btn">Ver Pedidos</a>
</div>

<h2>Últimos Utilizadores Registados</h2>
<table>
    <tr><th>Username</th><th>Email</th><th>Role</th></tr>
    <?php foreach ($latestUsers as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['role']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include_once '../includes/footer.php'; ?>
