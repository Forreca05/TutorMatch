<?php
session_start();
require_once '../../private/database/db.php'; // inclui a conexão à base de dados
require_once(__DIR__ . '/../../private/utils/csrf.php');


<<<<<<< HEAD
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/login.php');
    exit();
=======
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
>>>>>>> origin/master
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/manage_users.php?error=invalid_method');
    exit();
}
if (!verify_csrf_token($_POST['csrf_token'])) {
    header('Location: ../pages/manage_users.php?error=invalid_token');
    exit();
}

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header('Location: ../pages/manage_users.php?error=invalid_id');
    exit();
}

$userId = $_POST['id'];
$stmt = $db->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
$stmt->execute([$userId]);
header('Location: ../pages/manage_users.php');
exit;
