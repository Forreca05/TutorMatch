<?php
session_start();
require_once '../../private/database/db.php'; // conexão à base de dados

if (isset($_GET['id'])) {
  $serviceId = $_GET['id'];

  $stmt = $db->prepare("DELETE FROM services WHERE id = ?");
  $stmt->execute([$serviceId]);

  header("Location: ../pages/my_services.php");
  exit;
} else {
  echo "ID do serviço não especificado.";
}
