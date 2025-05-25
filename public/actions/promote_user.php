<?php
session_start();
require_once '../../private/database/db.php'; // inclui a conexão à base de dados

// Verifica se foi passado um id
if (isset($_GET['id'])) {
  $userId = $_GET['id'];

  // Atualiza o papel do utilizador para admin
  $stmt = $db->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
  $stmt->execute([$userId]);

  // Redireciona de volta para a lista de utilizadores
  header('Location: ../pages/manage_users.php');
  exit;
} else {
  echo "ID do utilizador não especificado.";
}
