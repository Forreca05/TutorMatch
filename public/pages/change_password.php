<?php
session_start();
require_once '../../private/database/db.php';

if (!isset($_SESSION['user_id'])) {
    die('Acesso negado.');
}

?>

<?php require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader(); ?>

<h2>Alterar Password</h2>

<form action="../actions/action_change_password.php" method="POST">
  <label for="current_password">Password Atual:</label><br>
  <input type="password" name="current_password" id="current_password" required><br><br>

  <label for="new_password">Nova Password:</label><br>
  <input type="password" name="new_password" id="new_password" required><br><br>

  <button type="submit">Alterar Password</button>
</form>

<?php drawFooter(); ?>
