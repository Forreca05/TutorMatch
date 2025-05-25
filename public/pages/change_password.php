<?php
session_start();
require_once '../../private/database/db.php';

if (!isset($_SESSION['user_id'])) {
  die('Acesso negado.');
}

?>

<?php require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader(); ?>

<div class="container-sm">
  <h2 class="text-center">Alterar Password</h2>

  <form action="../actions/action_change_password.php" method="POST" class="form">
    <div class="form-group">
      <label for="current_password" class="form-label">Password Atual:</label>
      <input type="password" name="current_password" id="current_password" class="form-input" required>
    </div>

    <div class="form-group">
      <label for="new_password" class="form-label">Nova Password:</label>
      <input type="password" name="new_password" id="new_password" class="form-input" required>
    </div>

    <button type="submit" class="btn btn-full">Alterar Password</button>
  </form>
</div>


<?php drawFooter(); ?>