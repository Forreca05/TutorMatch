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

<div class="container">
  <?php drawPageHeader('Pesquisar Serviços', 'Encontre o serviço perfeito para si'); ?>
  
  <form class="form" method="GET" action="search.php">
    <div class="form-row">
      <?php drawFormField('text', 'q', 'Pesquisar', $query, ['placeholder' => 'Pesquisar...']); ?>
      
      <?php 
      $categoryOptions = '<option value="">Todas as categorias</option>';
      foreach ($categories as $cat) {
        $selected = $category == $cat['id'] ? 'selected' : '';
        $categoryOptions .= '<option value="' . htmlspecialchars($cat['id']) . '" ' . $selected . '>' . 
                           ucfirst(htmlspecialchars($cat['name'])) . '</option>';
      }
      drawFormField('select', 'category', 'Categoria', $categoryOptions);
      ?>
    </div>
    
    <div class="form-row">
      <?php drawFormField('number', 'min_price', 'Preço Mínimo', $min_price, ['placeholder' => 'Preço mín.']); ?>
      <?php drawFormField('number', 'max_price', 'Preço Máximo', $max_price, ['placeholder' => 'Preço máx.']); ?>
    </div>
    
    <button type="submit" class="btn btn-primary">Filtrar</button>
  </form>

  <div class="results mt-lg">
    <?php if ($results): ?>
      <div class="card-list">
        <?php foreach ($results as $service): ?>
          <div class="card">
            <div class="card-body">
              <h3 class="card-title"><?= htmlspecialchars($service['title']) ?></h3>
              <p><?= htmlspecialchars($service['description']) ?></p>
              <p class="text-primary font-bold">€<?= number_format($service['price'], 2) ?></p>
              <a href="/pages/view_service.php?id=<?= $service['id'] ?>" class="btn btn-primary">Ver mais</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <?php drawEmptyState('Nenhum serviço encontrado com os filtros aplicados.', 'Ver Todos os Serviços', '/pages/search.php'); ?>
    <?php endif; ?>
  </div>
</div>

<?php drawFooter(); ?>