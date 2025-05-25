<?php
if (session_status() === PHP_SESSION_NONE) {
}

function drawProfilePicture($pic_path, $alt_text = 'Foto de perfil', $size = '120px')
{
?>
  <img src="<?= htmlspecialchars($pic_path ?: '/img/default.jpeg') ?>"
    alt="<?= htmlspecialchars($alt_text) ?>"
    class="profile-pic"
    style="width: <?= $size ?>; height: <?= $size ?>;">
<?php
}

function drawFormField($type, $name, $label, $value = '', $attributes = [], $required = false)
{
?>
  <div class="form-field">
    <label for="<?= htmlspecialchars($name) ?>" class="form-label">
      <?= htmlspecialchars($label) ?><?= $required ? ' *' : '' ?>
    </label>

    <?php if ($type === 'textarea'): ?>
      <textarea name="<?= htmlspecialchars($name) ?>"
        id="<?= htmlspecialchars($name) ?>"
        class="form-textarea"
        <?= $required ? 'required' : '' ?>
        <?php foreach ($attributes as $attr => $val): ?>
        <?= htmlspecialchars($attr) ?>="<?= htmlspecialchars($val) ?>"
        <?php endforeach; ?>><?= htmlspecialchars($value) ?></textarea>
    <?php elseif ($type === 'select'): ?>
      <select name="<?= htmlspecialchars($name) ?>"
        id="<?= htmlspecialchars($name) ?>"
        class="form-select"
        <?= $required ? 'required' : '' ?>
        <?php foreach ($attributes as $attr => $val): ?>
        <?= htmlspecialchars($attr) ?>="<?= htmlspecialchars($val) ?>"
        <?php endforeach; ?>>
        <?= $value ?> <!-- $value contains the options HTML -->
      </select>
    <?php else: ?>
      <input type="<?= htmlspecialchars($type) ?>"
        name="<?= htmlspecialchars($name) ?>"
        id="<?= htmlspecialchars($name) ?>"
        class="form-input"
        value="<?= htmlspecialchars($value) ?>"
        <?= $required ? 'required' : '' ?>
        <?php foreach ($attributes as $attr => $val): ?>
        <?= htmlspecialchars($attr) ?>="<?= htmlspecialchars($val) ?>"
        <?php endforeach; ?>>
    <?php endif; ?>
  </div>
<?php
}

function drawEmptyState($message, $action_text = null, $action_url = null)
{
?>
  <div class="empty-state">
    <p><?= htmlspecialchars($message) ?></p>
    <?php if ($action_text && $action_url): ?>
      <a href="<?= htmlspecialchars($action_url) ?>" class="btn btn-primary"><?= htmlspecialchars($action_text) ?></a>
    <?php endif; ?>
  </div>
  <?php
}

function drawDataTable($headers, $rows, $empty_message = 'Nenhum dado encontrado.')
{
  if (empty($rows)): ?>
    <?php drawEmptyState($empty_message); ?>
  <?php else: ?>
    <table class="data-table">
      <thead>
        <tr>
          <?php foreach ($headers as $header): ?>
            <th><?= htmlspecialchars($header) ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $row): ?>
          <tr>
            <?php foreach ($row as $cell): ?>
              <td><?= $cell ?></td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif;
}

function drawPageHeader($title, $subtitle = null, $action_button = null)
{
  ?>
  <div class="page-header text-center mb-lg">
    <h1 class="mb-sm"><?= htmlspecialchars($title) ?></h1>
    <?php if ($subtitle): ?>
      <p class="text-muted mb"><?= htmlspecialchars($subtitle) ?></p>
    <?php endif; ?>
    <?php if ($action_button): ?>
      <?= $action_button ?>
    <?php endif; ?>
  </div>
<?php
}

function drawCard($content, $title = null, $footer = null, $css_class = '')
{
?>
  <div class="card <?= htmlspecialchars($css_class) ?>">
    <?php if ($title): ?>
      <div class="card-header">
        <h3 class="mb-0"><?= htmlspecialchars($title) ?></h3>
      </div>
    <?php endif; ?>
    <div class="card-body">
      <?= $content ?>
    </div>
    <?php if ($footer): ?>
      <div class="card-footer">
        <?= $footer ?>
      </div>
    <?php endif; ?>
  </div>
<?php
}
?>

<?php function drawHeader()
{ ?>

  <!DOCTYPE html>
  <html lang="pt">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TutorMatch</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/style.css" />
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
              <?php drawProfilePicture($_SESSION['profile_pic'] ?? '/img/default.jpeg', 'Perfil', '40px'); ?>
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

  <?php function drawFooter()
  { ?>
    <footer>
      <p>&copy; 2025 TutorMatch. Todos os direitos reservados.</p>
    </footer>
    <script src="/js/main.js"></script>
  </body>

  </html>
<?php } ?>