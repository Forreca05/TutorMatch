<?php
session_start();
require_once '../../private/database/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') {
  die('Acesso negado.');
}

if (!isset($_GET['id'])) {
  die('Serviço não especificado.');
}

$service_id = intval($_GET['id']);

// Buscar dados do serviço
$stmt = $db->prepare("SELECT * FROM services WHERE id = ? AND user_id = ?");
$stmt->execute([$service_id, $_SESSION['user_id']]);
$service = $stmt->fetch();

if (!$service) {
  die('Serviço não encontrado ou não pertence a si.');
}

// Buscar categorias
$catStmt = $db->query("SELECT id, name FROM categories ORDER BY name");
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

// Se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $description = trim($_POST['description']);
  $price = floatval($_POST['price']);
  $delivery_time = intval($_POST['delivery_time']);
  $category_id = intval($_POST['category_id']);

  // Upload de imagem (opcional)
  $image_path = $service['image_path'];
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['image']['tmp_name'];
    $filename = uniqid() . '_' . basename($_FILES['image']['name']);
    $destination = '../uploads/' . $filename;
    move_uploaded_file($tmp_name, $destination);
    $image_path = $destination;
  }

  // Atualizar serviço
  $update = $db->prepare("
        UPDATE services 
        SET title = ?, description = ?, price = ?, delivery_time = ?, category_id = ?, image_path = ? 
        WHERE id = ? AND user_id = ?
    ");
  $update->execute([
    $title,
    $description,
    $price,
    $delivery_time,
    $category_id,
    $image_path,
    $service_id,
    $_SESSION['user_id']
  ]);

  header("Location: view_service.php?id=$service_id&edit=success");
  exit;
}
?>

<?php require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader(); ?>
<link rel="stylesheet" href="/css/edit_service.css">

<div class="edit-service-container">
  <h2>Editar Serviço</h2>

  <form method="POST" enctype="multipart/form-data" class="edit-service-form">
    <label for="title">Título</label>
    <input type="text" id="title" name="title" value="<?= htmlspecialchars($service['title']) ?>" required>

    <label for="description">Descrição</label>
    <textarea id="description" name="description" required><?= htmlspecialchars($service['description']) ?></textarea>

    <label for="price">Preço (€)</label>
    <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($service['price']) ?>" required>

    <label for="delivery_time">Tempo de Entrega (dias)</label>
    <input type="number" id="delivery_time" name="delivery_time" value="<?= htmlspecialchars($service['delivery_time']) ?>" required>

    <label for="category_id">Categoria</label>
    <select id="category_id" name="category_id" required>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>" <?= $service['category_id'] == $cat['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label for="image">Imagem (opcional)</label>
    <input type="file" id="image" name="image">

    <?php if (!empty($service['image_path'])): ?>
      <p>Imagem atual:</p>
      <img src="<?= $service['image_path'] ?>" alt="Imagem atual" style="max-width: 200px;">
    <?php endif; ?>

    <button type="submit">Atualizar Serviço</button>
  </form>
</div>

<?php drawFooter(); ?>