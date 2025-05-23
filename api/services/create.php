<?php
require_once '../../database/db.php';
require_once '../auth.php';

header('Content-Type: application/json');

if (!isAuthorized()) sendUnauthorized();

// Ler JSON
$input = json_decode(file_get_contents("php://input"), true);

$title = $input['title'] ?? null;
$description = $input['description'] ?? null;
$user_id = $input['user_id'] ?? 1; // Em breve vamos autenticar de verdade

if (!$title || !$description) {
    http_response_code(400);
    echo json_encode(['error' => 'Title and description required']);
    exit;
}

$stmt = $db->prepare("INSERT INTO services (user_id, category_id, title, description, created_at)
                      VALUES (?, 1, ?, ?, datetime('now'))");
$stmt->execute([$user_id, $title, $description]);

$serviceId = $db->lastInsertId();

echo json_encode(['success' => true, 'id' => $serviceId]);
