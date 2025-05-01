<?php include_once '../includes/header.php'; ?>
<h2>Inicia sessão</h2>

<form action="../actions/action_login.php" method="post">
  <input type="text" name="username" placeholder="Username" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit">Entrar</button>
</form>

<?php if (isset($_GET['error'])): ?>
  <p id="error-message" style="color:red;">
    <?php echo htmlspecialchars($_GET['error']); ?>
  </p>
<?php endif; ?>

<script>
  setTimeout(function() {
    const errorMessage = document.getElementById('error-message');
    if (errorMessage) {
      errorMessage.style.display = 'none';
    }
  }, 4000); 
</script>

<?php include_once '../includes/footer.php'; ?>
