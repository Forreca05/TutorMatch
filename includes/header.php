<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TutorMatch</title>
  <link rel="stylesheet" href="/css/main.css" />
  <link rel="stylesheet" href="/css/navbar.css" />
</head>
<body>
  <header>
  <div class="header-left">
    <h1><a href="/index.php">TutorMatch</a></h1>

    <form id="search-form" action="/index.php" method="GET" autocomplete="off">
      <input type="text" id="search-input" name="q" placeholder="Procurar serviços...">
      <div id="suggestions-box" class="suggestions-box"></div>
    </form>
  </div>

  <nav class="navbar">
    <div class="nav-right">    
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="/index.php" class="nav-icon home-icon" title="Início">
            <img src="/img/icons/home-white.png" alt="Início">
          </a>
          <a href="/pages/profile.php" class="profile-pic">
            <img src="<?= $_SESSION['profile_pic'] ?? '/img/default.jpeg' ?>" alt="Perfil">
          </a>
          <a href="/pages/conversations.php" class="nav-icon chat-icon" title="Mensagens">
            <img src="/img/icons/white-balloon.png" alt="Mensagens">
          </a>

          <button id="menu-toggle" class="menu-btn">&#9776;</button>

          <div id="dropdown-menu" class="dropdown-menu">
            <?php if ($_SESSION['role'] == 'freelancer'): ?>
              <a href="/actions/switch_role.php">Mudar para Cliente</a>
              <a href="/pages/create_service.php">Criar Serviço</a>
              <a href="/pages/freelancer_dashboard.php">Painel</a>

            <?php elseif ($_SESSION['role'] == 'client'): ?>
              <a href="/actions/switch_role.php">Mudar para Freelancer</a>
              <a href="/pages/available_services.php">Serviços</a>
              <a href="/pages/orders.php">Minhas Encomendas</a>

            <?php elseif ($_SESSION['role'] == 'admin'): ?>
              <a href="/pages/admin_dashboard.php">Painel</a>
            <?php endif; ?>

            <a href="/pages/terms_and_policy.php">Termos &amp; Privacidade</a>
            <a href="/pages/logout.php">Sair</a>
          </div>
        <?php else: ?>
          <a href="/pages/login.php">Entrar</a>
          <a href="/pages/register.php">Registrar</a>
        <?php endif; ?>
      </div>
    </nav>
  </header>

<script src="/js/navbar.js"></script>


