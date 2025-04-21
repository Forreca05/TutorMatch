<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="eng">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TutorMatch</title>
  <link rel="stylesheet" href="/css/style.css" />
</head>
<body>
  <header>
    <h1><a href="/index.php">TutorMatch</a></h1>
    <nav>
      <a href="/index.php">Início</a>
      <a href="../pages/services.php">Serviços</a>
      <?php if (isset($_SESSION['user_id'])): ?>
              <div class="user-info">
                <a href="../pages/profile.php">Meu Perfil</a>
                <a href="../pages/logout.php">Sair</a>
              </div>
      <?php else: ?>
              <a href="/pages/login.php">Entrar</a>
              <a href="/pages/register.php">Registrar</a>
      <?php endif; ?>
    </nav>
  </header>
