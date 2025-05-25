<?php
session_start();
require_once '../../private/database/db.php';

if (!isset($_SESSION['user_id'])) {
    die('Acesso negado.');
}

?>

<?php require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader(); ?>

<link rel="stylesheet" href="../css/change_password.css">
<div class="password-change-container">
  <h2 class="password-change-container__title">Alterar Password</h2>

  <form action="../actions/action_change_password.php" method="POST">
    <label for="current_password">Password Atual:</label>
    <input type="password" name="current_password" id="current_password" required>

    <label for="new_password">Nova Password:</label>
    <input type="password" name="new_password" id="new_password" required>

    <button type="submit">Alterar Password</button>
  </form>
</div>


<?php drawFooter(); ?>
