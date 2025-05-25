<?php
session_start();
require_once '../../private/database/db.php';
require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader();


if (isset($_GET['q'])) {
  if (!preg_match("/^[a-zA-Z0-9\s]+$/", $_GET['q'])) {
    $query = preg_replace("/^[a-zA-Z0-9\s]+$/", '', $_GET['q']);
  } else {
    $query = $_GET['q'];
  }
} else {
  $query = '';
}

if (isset($_GET['min_price'])) {
  if (!preg_match("/^[a-zA-Z0-9\s]+$/", $_GET['min_price'])) {
    $min_price = preg_replace("/^[a-zA-Z0-9\s]+$/", '', $_GET['min_price']);
  } else {
    $min_price = $_GET['min_price'];
  }
} else {
  $min_price = '';
}

if (isset($_GET['max_price'])) {
  if (!preg_match("/^[a-zA-Z0-9\s]+$/", $_GET['max_price'])) {
    $max_price = preg_replace("/^[a-zA-Z0-9\s]+$/", '', $_GET['max_price']);
  } else {
    $max_price = $_GET['max_price'];
  }
} else {
  $max_price = '';
}

if (isset($_GET['category'])) {
  if (!preg_match("/^[a-zA-Z0-9\s]+$/", $_GET['category'])) {
    $category = preg_replace("/^[a-zA-Z0-9\s]+$/", '', $_GET['category']);
  } else {
    $category = $_GET['category'];
  }
} else {
  $category = '';
}

$catStmt = $db->query("
  SELECT DISTINCT c.id, c.name 
  FROM categories c
  JOIN services s ON s.category_id = c.id
  ORDER BY c.name
");
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM services WHERE 1=1";
$params = [];

if (!empty($query)) {
  $sql .= " AND title LIKE :query";
  $params[':query'] = '%' . $query . '%';
}
if (!empty($category)) {
  $sql .= " AND category_id = :category";
  $params[':category'] = $category;
}
if (is_numeric($min_price)) {
  $sql .= " AND price >= :min_price";
  $params[':min_price'] = $min_price;
}
if (is_numeric($max_price)) {
  $sql .= " AND price <= :max_price";
  $params[':max_price'] = $max_price;
}

$stmt = $db->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();
?>

<link rel="stylesheet" href="/css/search.css">

<div class="search-container">
  <h2>Pesquisar Serviços</h2>
  <form class="search-form" method="GET" action="search.php">
    <input id="search-input2" type="text" name="q" placeholder="Pesquisar..." value="<?= htmlspecialchars($query) ?>">
    <select id="category-select" name="category">
      <option value="">Todas as categorias</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= htmlspecialchars($cat['id']) ?>" <?= $category == $cat['id'] ? 'selected' : '' ?>>
          <?= ucfirst(htmlspecialchars($cat['name'])) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <input type="number" id="min_price" name="min_price" placeholder="Preço mín." value="<?= htmlspecialchars($min_price) ?>">
    <input type="number" id="max_price" name="max_price" placeholder="Preço máx." value="<?= htmlspecialchars($max_price) ?>">
    <button type="submit">Filtrar</button>
  </form>

  <div class="results">
    <?php if ($results): ?>
      <?php foreach ($results as $service): ?>
        <div class="service">
          <h3><?= htmlspecialchars($service['title']) ?></h3>
          <p><?= htmlspecialchars($service['description']) ?></p>
          <p><strong>Preço:</strong> €<?= number_format($service['price'], 2) ?></p>
          <a href="/pages/view_service.php?id=<?= $service['id'] ?>">Ver mais »</a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Nenhum serviço encontrado com os filtros aplicados.</p>
    <?php endif; ?>
  </div>
</div>

<script src="../js/ajaxsearch.js"></script>

<?php drawFooter(); ?>