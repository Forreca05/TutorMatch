<?php
require_once '../../private/database/db.php';
require_once(__DIR__ . '/../templates/service_card.tpl.php');
session_start();

$userId = intval($_GET['id'] ?? 0);
if (!$userId) die('Utilizador inválido.');

$stmt = $db->prepare("
  SELECT s.*, c.name AS category 
  FROM services s 
  LEFT JOIN categories c ON s.category_id = c.id
  WHERE s.user_id = ?
  ORDER BY s.created_at DESC
");
$stmt->execute([$userId]);
$services = $stmt->fetchAll();
require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader();
?>
<h2>Serviços oferecidos por <?= htmlspecialchars($services[0]['username'] ?? 'Utilizador') ?></h2>

<link rel="stylesheet" href="/css/services.css">
<div class="card-list">
  <?php foreach ($services as $s): 
    drawServiceCard($s, false, false);
  endforeach; ?>
</div>
<?php drawFooter(); ?>