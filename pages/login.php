<?php include_once '../includes/header.php'; ?>
<h2>Inicia sessão</h2>

<form action="../actions/action_login.php" method="post">
  <input type="text" name="username" placeholder="Username" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit">Entrar</button>
</form>

<?php if (isset($_GET['error'])): ?>
  <p class="flash-message error">
    <?= htmlspecialchars($_GET['error']); ?>
  </p>
<?php endif; ?>

<script src="../js/messages.js"></script>

<?php include_once '../includes/footer.php'; ?>
