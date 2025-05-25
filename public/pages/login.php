<?php
require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader();
?>

<link rel="stylesheet" href="../css/login.css">

<div class="container-sm text-center">
  <h2>Bem-vindo de volta!</h2>

  <form action="../actions/action_login.php" method="post" class="form">
    <input type="text" name="username" placeholder="Nome de utilizador ou Email" class="form-input" required>
    <input type="password" name="password" placeholder="Palavra-passe" class="form-input" required>
    <button type="submit" class="btn btn-primary btn-full">Entrar</button>

    <?php if (isset($_GET['error'])): ?>
      <p class="message message-error">
        <?= htmlspecialchars($_GET['error']); ?>
      </p>
    <?php endif; ?>
  </form>
</div>

<script src="../js/messages.js"></script>

<?php drawFooter(); ?>