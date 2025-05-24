<?php
require_once '../../private/database/db.php';

$query = $_GET['q'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$category = $_GET['category'] ?? '';

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

header('Content-Type: application/json');

echo json_encode($results);