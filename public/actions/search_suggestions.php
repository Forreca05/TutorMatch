<?php
require_once '../../private/database/db.php';

$q = trim($_GET['q'] ?? '');

if (strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

$stmt = $db->prepare("SELECT id, title FROM services WHERE title LIKE ? LIMIT 10");
$stmt->execute(["%$q%"]);
$suggestions = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($suggestions);
