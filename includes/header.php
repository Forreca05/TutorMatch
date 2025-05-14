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
  <link rel="stylesheet" href="/css/main.css" />
</head>
<body>
  <header>
    <h1><a href="/index.php">TutorMatch</a></h1>
    <nav>
      <a href="/index.php">Início</a>
      
      <?php if ($_SESSION['role'] == 'freelancer'): ?>
        <a href="../actions/switch_role.php">Mudar para Cliente</a>
        <a href="../pages/create_service.php">Criar Serviço</a>
        <a href="../pages/my_services.php">Meus Serviços</a>
        <a href="../pages/orders.php">Pedidos Recebidos</a>

      <?php elseif ($_SESSION['role'] == 'client'): ?>
        <a href="../actions/switch_role.php">Mudar para Freelancer</a>
        <a href="../pages/available_services.php">Serviços</a>
        <a href="../pages/orders.php">Minhas Encomendas</a>

      <?php elseif ($_SESSION['role'] == 'admin'): ?>
        <div class="admin-menu">
            <a href="../pages/admin_dashboard.php">Painel</a>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['user_id'])): ?>
        <div class="user-info">
          <a href="../pages/profile.php">Meu Perfil</a>
          <a href="../pages/logout.php">Sair</a>
        </div>

      <?php else: ?>
        <a href="../pages/login.php">Entrar</a>
        <a href="../pages/register.php">Registrar</a>
      <?php endif; ?>

    
    </nav>

  </header>
