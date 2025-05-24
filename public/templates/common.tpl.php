<?php
  if (session_status() === PHP_SESSION_NONE) {
    
  }
?>

<?php function drawHeader() { ?>

  <!DOCTYPE html>
  <html lang="pt">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      <title>TutorMatch</title>
      <link rel="stylesheet" href="../css/main.css" />
      <link rel="stylesheet" href="../css/navbar.css" />
    </head>
    <body>
      <header class="main-header">

        <!-- top bar: logo -->
        <div class="top-bar">
          <h1 class="logo">
            <a href="/index.php">TutorMatch</a>
          </h1>
        </div>

        <!-- search row -->
        <div class="search-row">
          <form id="search-form" action="/pages/search.php" method="GET" autocomplete="off">
            <input type="text" id="search-input" name="q" placeholder="Procurar serviços...">
            <div id="suggestions-box" class="suggestions-box"></div>
          </form>
        </div>

        <label class="dark-mode-toggle">
          <input type="checkbox" id="darkModeSwitch" />
          <span class="slider"></span>
        </label>

        <!-- desktop-only nav icons + dropdown -->
        <nav class="navbar">
          <div class="nav-right">
            <?php if (isset($_SESSION['user_id'])): ?>

            <a href="/pages/profile.php" class="nav-icon profile-pic" title="Perfil">
              <img src="<?= $_SESSION['profile_pic'] ?? '/img/default.jpeg' ?>" alt="Perfil">
            </a>
            <a href="/pages/conversations.php" class="nav-icon chat-icon" title="Mensagens">
              <img src="/img/icons/white-balloon.png" alt="Mensagens">
            </a>
            <button id="menu-toggle" class="menu-btn" aria-label="Toggle menu">
              &#9776;
            </button>

            <div id="dropdown-menu" class="dropdown-menu">
              <a href="/pages/profile.php">Perfil</a>

              <?php if ($_SESSION['role'] == 'freelancer'): ?>
                <a href="/actions/switch_role.php">Mudar para Cliente</a>
                <a href="/pages/create_service.php">Criar Serviço</a>
                <a href="/pages/freelancer_dashboard.php">Painel</a>
              <?php elseif ($_SESSION['role'] == 'client'): ?>
                <a href="/actions/switch_role.php">Mudar para Freelancer</a>
                <a href="/pages/search.php">Serviços</a>
                <a href="/pages/orders.php">Minhas Encomendas</a>
              <?php elseif ($_SESSION['role'] == 'admin'): ?>
                <a href="/pages/admin_dashboard.php">Painel</a>
              <?php endif; ?>

              <a href="/pages/terms_and_policy.php">Termos &amp; Privacidade</a>
              <a href="/pages/logout.php">Sair</a>
            </div>
            <?php else: ?>
            <button id="menu-toggle" class="menu-btn" aria-label="Toggle menu">
              &#9776;
            </button>
            <div id="dropdown-menu" class="dropdown-menu">
              <a href="/pages/login.php">Entrar</a>   
              <a href="/pages/register.php">Registrar</a>
            </div>
            <?php endif; ?>
          </div>
        </nav>

      </header>

      <script src="/js/darkmode.js"></script>
      <script src="/js/navbar.js"></script>

<?php } ?>

<?php function drawFooter() { ?>
      <footer>
        <p>&copy; 2025 TutorMatch. Todos os direitos reservados.</p>
      </footer>
      <script src="/js/main.js"></script>
    </body>
  </html>
<?php } ?>