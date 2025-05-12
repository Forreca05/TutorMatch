<?php
// File: create_service.php
session_start();
require_once '../database/db.php';

// Verifica se o utilizador está autenticado e é freelancer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') {
    header('Location: ../pages/login.php');
    exit;
}

// Busca categorias ordenadas alfabeticamente
$stmt = $db->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll();
?>

<?php include_once '../includes/header.php'; ?>
<link rel="stylesheet" href="../css/services.css"> <!-- CSS específico para esta página -->

<div class="create-service-container">
  <h2>Criar Novo Serviço</h2>
  <form action="../actions/create_service_action.php" method="POST" enctype="multipart/form-data" class="create-service-form">
    
    <label for="title">Título:</label>
    <input type="text" name="title" id="title" placeholder="Título do Serviço" required>

    <label for="category">Categoria:</label>
    <select name="category_id" id="category" required>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
      <?php endforeach; ?>
    </select>

    <label for="description">Descrição:</label>
    <textarea name="description" id="description" placeholder="Descrição do serviço" required></textarea>

    <label for="price">Preço (€):</label>
    <input type="number" name="price" id="price" placeholder="Preço" required>

    <label for="delivery_time">Tempo de Entrega (dias):</label>
    <input type="number" name="delivery_time" id="delivery_time" placeholder="Dias para entrega" required>

    <label for="image">Imagem (opcional):</label>
    <input type="file" name="image" id="image" accept="image/*">

    <button type="submit">Criar Serviço</button>
  </form>
</div>

<?php include_once '../includes/footer.php'; ?>
