<?php
session_start();
require_once '../../private/database/db.php'; // inclui a conexão à base de dados

// Verifica se foi passado um id
if (isset($_GET['id'])) {
  $userId = $_GET['id'];

  // Começa uma transação para garantir que ambas as ações ocorram juntas
  $db->beginTransaction();

  try {
    // Deleta todos os serviços do utilizador
    $deleteStmt = $db->prepare("DELETE FROM services WHERE user_id = ?");
    $deleteStmt->execute([$userId]);

    // Atualiza o papel do utilizador para admin
    $updateStmt = $db->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
    $updateStmt->execute([$userId]);

    // Confirma a transação
    $db->commit();

    // Redireciona de volta para a lista de utilizadores
    header('Location: ../pages/manage_users.php');
    exit;
  } catch (PDOException $e) {
    // Reverte a transação em caso de erro
    $db->rollBack();
    echo "Erro ao promover o utilizador: " . $e->getMessage();
  }

} else {
  echo "ID do utilizador não especificado.";
}
