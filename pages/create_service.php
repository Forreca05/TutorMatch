<?php
// File: create_service.php
session_start();
require_once '../database/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') {
    header('Location: ../pages/login.php');
    exit;
}

$categories = $db->query("SELECT * FROM categories")->fetchAll();
?>

<?php include_once '../includes/header.php'; ?>
<h2>Criar Novo Serviço</h2>
<form action="../actions/create_service_action.php" method="POST" enctype="multipart/form-data">
  <input type="text" name="title" placeholder="Título do Serviço" required><br>
  <select name="category_id" required>
    <?php foreach ($categories as $cat): ?>
      <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
    <?php endforeach; ?>
  </select><br>
  <textarea name="description" placeholder="Descrição do serviço" required></textarea><br>
  <input type="number" name="price" placeholder="Preço (€)" required><br>
  <input type="number" name="delivery_time" placeholder="Tempo de entrega (dias)" required><br>
  <input type="file" name="image" accept="image/*"><br>
  <button type="submit">Criar Serviço</button>
</form>
<?php include_once '../includes/footer.php'; ?>