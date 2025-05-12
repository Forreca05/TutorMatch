<?php
require_once '../database/db.php';

$conditions = [];
$params = [];

if (!empty($_GET['category_id'])) {
    $conditions[] = 's.category_id = ?';
    $params[] = $_GET['category_id'];
}
if (!empty($_GET['min_price'])) {
    $conditions[] = 's.price >= ?';
    $params[] = $_GET['min_price'];
}
if (!empty($_GET['max_price'])) {
    $conditions[] = 's.price <= ?';
    $params[] = $_GET['max_price'];
}

$where = count($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
$sql = "
    SELECT s.*, u.username, c.name as category_name,
    IFNULL(AVG(r.rating), 0) as avg_rating
    FROM services s
    JOIN users u ON s.user_id = u.id
    JOIN categories c ON s.category_id = c.id
    LEFT JOIN reviews r ON r.service_id = s.id
    $where
    GROUP BY s.id
    ORDER BY avg_rating DESC
";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$services = $stmt->fetchAll();

// Carrega categorias para o filtro
$categories = $db->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>
<!-- Mostra formulário de filtros e lista de serviços como já tens -->
