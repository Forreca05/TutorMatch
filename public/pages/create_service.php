<?php
// File: create_service.php
session_start();
require_once '../../private/database/db.php';

// Verifica se o utilizador está autenticado e é freelancer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') {
  header('Location: ../pages/login.php');
  exit;
}

// Busca categorias ordenadas alfabeticamente
$stmt = $db->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll();
?>

<?php require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader(); ?>

<div class="container-sm">
  <h2 class="text-center">Criar Novo Serviço</h2>
  
  <form action="../actions/create_service_action.php" method="POST" enctype="multipart/form-data" class="form">
    <div class="form-group">
      <label for="title" class="form-label">Título:</label>
      <input type="text" name="title" id="title" placeholder="Título do Serviço" class="form-input" required>
    </div>

    <div class="form-group">
      <label for="category" class="form-label">Categoria:</label>
      <select name="category_id" id="category" class="form-input form-select" required>
        <option value="" disabled selected>Select a category</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="description" class="form-label">Descrição:</label>
      <textarea name="description" id="description" placeholder="Descrição do serviço" class="form-input form-textarea" required></textarea>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="price" class="form-label">Preço (€):</label>
        <input type="number" name="price" id="price" placeholder="Preço" class="form-input" required>
      </div>
      
      <div class="form-group">
        <label for="delivery_time" class="form-label">Tempo de Entrega (dias):</label>
        <input type="number" name="delivery_time" id="delivery_time" placeholder="Dias para entrega" class="form-input" required>
      </div>
    </div>

    <div class="form-group">
      <label for="image" class="form-label">Imagem (opcional):</label>
      <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png" class="form-input">
    </div>

    <button type="submit" class="btn btn-primary btn-full">Criar Serviço</button>
  </form>
</div>

<?php drawFooter(); ?>