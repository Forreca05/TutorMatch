<?php
session_start();
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../../private/utils/csrf.php');

drawHeader();
?>

<link rel="stylesheet" href="../css/login.css">

<div class="login-container">
  <h2>Bem-vindo de volta!</h2>

  <form action="../actions/action_login.php" method="post" class="login-form">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    <input type="text" name="username" placeholder="Nome de utilizador ou Email" required>
    <input type="password" name="password" placeholder="Palavra-passe" required>
    <button type="submit">Entrar</button>

    <?php if (isset($_GET['error'])): ?>
      <p class="flash-message error">
        <?= htmlspecialchars($_GET['error']); ?>
      </p>
    <?php endif; ?>
  </form>
</div>

<script src="../js/messages.js"></script>

<?php drawFooter(); ?>