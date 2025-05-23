<?php
require_once '../../database/db.php';
require_once '../auth.php';

header('Content-Type: application/json');

if (!isAuthorized()) sendUnauthorized();

$stmt = $db->query("SELECT id, title, description FROM services LIMIT 50");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($services);
