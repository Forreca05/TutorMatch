<?php include_once '../includes/header.php'; ?>
<h2>Regista-te</h2>

<form action="../actions/action_register.php" method="post">
  <input type="text" name="username" placeholder="Username" required>
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit">Criar conta</button>
</form>
<?php include_once '../includes/footer.php'; ?>
