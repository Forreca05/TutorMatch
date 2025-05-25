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

<?php 
require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader('Criar Novo Serviço'); 
?>

<div class="container-sm">
  <div class="page-header">
    <h2 class="text-center">Criar Novo Serviço</h2>
  </div>
  
  <form action="../actions/create_service_action.php" method="POST" enctype="multipart/form-data" class="form">
    <?php 
    drawFormField('text', 'title', 'Título', '', ['placeholder' => 'Título do Serviço'], true);
    
    // Generate category options
    $categoryOptions = '<option value="" disabled selected>Selecione uma categoria</option>';
    foreach ($categories as $cat) {
      $categoryOptions .= '<option value="' . htmlspecialchars($cat['id']) . '">' . htmlspecialchars($cat['name']) . '</option>';
    }
    drawFormField('select', 'category_id', 'Categoria', $categoryOptions, [], true);
    
    drawFormField('textarea', 'description', 'Descrição', '', ['placeholder' => 'Descrição do serviço'], true);
    ?>

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