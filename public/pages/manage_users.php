<?php
session_start();
require_once(__DIR__ . '/../../private/utils/csrf.php');
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

<div class="container">
  <h1>Gerir Utilizadores</h1>

  <table class="table">
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
        <td>

          <?php
          echo htmlspecialchars(
            $user['role'] === 'admin' ? 'Admin' : 'Client/Freelancer'
          );
          ?>
        </td>
        <td>
          <?php if ($user['role'] !== 'admin'): ?>
            <form action="../actions/promote_user.php" method="post" style="display:inline;" onsubmit="return confirm('Tem a certeza que quer Promover')">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                    <button type="submit" >Promover a admin </button>
            </form>
            <form action="../actions/delete_user.php" method="post" style="display:inline;" onsubmit="return confirm('Tem a certeza que quer eliminar?')">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                <button type="submit" >Eliminar</button>
            </form>
          <?php endif; ?>
        </td>
      </tr>

    <?php endforeach; ?>
  </table>
</div>
<?php drawFooter(); ?>