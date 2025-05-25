<?php
// File: create_service.php
session_start();
require_once '../../private/database/db.php';
require_once(__DIR__ . '/../../private/utils/csrf.php');

// Verifica se o utilizador está autenticado e é freelancer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') {
  header('Location: ../pages/login.php');
  exit;
}

// Busca categorias ordenadas alfabeticamente
$stmt = $db->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll();
?>

<?php 
require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader('Criar Novo Serviço'); 
?>

<div class="container-sm">
  <div class="page-header">
    <h2 class="text-center">Criar Novo Serviço</h2>
  </div>
  
  <form action="../actions/create_service_action.php" method="POST" enctype="multipart/form-data" class="form">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
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
      <?php 
      drawFormField('number', 'price', 'Preço (€)', '', ['placeholder' => 'Preço', 'step' => '0.01', 'min' => '0'], true);
      drawFormField('number', 'delivery_time', 'Tempo de Entrega (dias)', '', ['placeholder' => 'Dias para entrega', 'min' => '1'], true);
      ?>
    </div>

    <?php 
    drawFormField('file', 'image', 'Imagem (opcional)', '', ['accept' => '.jpg,.jpeg,.png,.mp4,.gif']);
    ?>

    <button type="submit" class="btn btn-primary w-full">Criar Serviço</button>
  </form>
</div>

<?php drawFooter(); ?>