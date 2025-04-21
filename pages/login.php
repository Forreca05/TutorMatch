<?php include_once '../includes/header.php'; ?>
<h2>Inicia sessão</h2>

<form action="../actions/action_login.php" method="post">
  <input type="text" name="username" placeholder="Username" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit">Entrar</button>
</form>
<?php include_once '../includes/footer.php'; ?>
