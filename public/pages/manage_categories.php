<?php
session_start();
require_once '../../private/database/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  die('Acesso negado.');
}

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  if (!empty($name)) {
    try {
      $stmt = $db->prepare("INSERT INTO categories (name) VALUES (?)");
      $stmt->execute([$name]);
      $success = "Categoria adicionada com sucesso.";
    } catch (PDOException $e) {
      $error = "Erro: " . $e->getMessage();
    }
  } else {
    $error = "O nome da categoria não pode estar vazio.";
  }
}

// Buscar categorias ordenadas alfabeticamente
$stmt = $db->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll();
?>

<?php require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader(); ?>

<div class="container-md">
  <h2>Gestão de Categorias</h2>

  <?php if ($success): ?>
    <p class="message message-success"><?= $success ?></p>
  <?php elseif ($error): ?>
    <p class="message message-error"><?= $error ?></p>
  <?php endif; ?>

  <form method="post" action="" class="form">
    <div class="form-row">
      <input type="text" name="name" placeholder="Nova categoria" class="form-input" required>
      <button type="submit" class="btn">Adicionar</button>
    </div>
  </form>

  <table class="table">
    <thead>
      <tr>
        <th>Nome da Categoria</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($categories as $category): ?>
        <tr>
          <td><?= htmlspecialchars($category['name']); ?></td>
          <td>
            <a href="../actions/delete_category.php?id=<?= $category['id']; ?>" onclick="return confirm('Apagar esta categoria?');" class="btn btn-danger btn-sm">Eliminar</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script src="../js/messages.js"></script>

<?php drawFooter(); ?>