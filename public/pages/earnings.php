<?php
session_start();
require_once '../../private/database/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') die('Acesso negado.');

$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT SUM(price) AS total FROM orders o JOIN services s ON o.service_id = s.id WHERE s.user_id = ? AND o.status = 'Concluído'");
$stmt->execute([$user_id]);
$total = $stmt->fetchColumn();

require_once(__DIR__ . '/../templates/common.tpl.php');
drawHeader();
?>
<h2>Ganhos Totais</h2>
<p>€<?= number_format($total ?? 0, 2) ?></p>
<?php drawFooter(); ?>
