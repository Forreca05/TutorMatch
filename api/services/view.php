<?php
require_once '../../private/database/db.php';
require_once '../auth.php';

header('Content-Type: application/json');

if (!isAuthorized()) sendUnauthorized();

$id = $_GET['id'] ?? null;
if (!$id) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing ID']);
  exit;
}

$stmt = $db->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
  http_response_code(404);
  echo json_encode(['error' => 'Service not found']);
  exit;
}

echo json_encode($service);
