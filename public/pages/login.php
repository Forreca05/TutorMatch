<?php include_once '../includes/header.php'; ?>
<link rel="stylesheet" href="../css/login.css">

<div class="login-container">
  <h2>Bem-vindo de volta!</h2>

  <form action="../actions/action_login.php" method="post" class="login-form">
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
<?php include_once '../includes/footer.php'; ?>
