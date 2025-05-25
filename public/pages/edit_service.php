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

<div class="container">
  <?php drawPageHeader('Editar Serviço', 'Atualize os detalhes do seu serviço'); ?>

  <form method="POST" enctype="multipart/form-data" class="form">
    <?php 
    drawFormField('text', 'title', 'Título', $service['title'], ['placeholder' => 'Ex: Desenvolvimento de Website'], true);
    drawFormField('textarea', 'description', 'Descrição', $service['description'], ['rows' => '5', 'placeholder' => 'Descreva detalhadamente o seu serviço...'], true);
    drawFormField('number', 'price', 'Preço (€)', $service['price'], ['step' => '0.01', 'min' => '0'], true);
    drawFormField('number', 'delivery_time', 'Tempo de Entrega (dias)', $service['delivery_time'], ['min' => '1'], true);
    
    $categoryOptions = '';
    foreach ($categories as $cat) {
      $selected = $service['category_id'] == $cat['id'] ? 'selected' : '';
      $categoryOptions .= '<option value="' . $cat['id'] . '" ' . $selected . '>' . htmlspecialchars($cat['name']) . '</option>';
    }
    drawFormField('select', 'category_id', 'Categoria', $categoryOptions, [], true);
    ?>

    <?php if (!empty($service['image_path'])): ?>
      <div class="current-image mb">
        <p class="form-label">Imagem atual:</p>
        <img src="<?= $service['image_path'] ?>" 
             alt="Imagem atual" 
             class="rounded" 
             style="max-width: 200px;">
      </div>
    <?php endif; ?>

    <?php drawFormField('file', 'image', 'Imagem (opcional)', '', ['accept' => 'image/*']); ?>


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
            <img src="../uploads/<?php echo htmlspecialchars($service['image_path']); ?>" alt="Imagem atual" style="max-width: 200px;">
        <?php endif; ?>

        <button type="submit">Atualizar Serviço</button>
    </form>
</div>

<?php drawFooter(); ?>