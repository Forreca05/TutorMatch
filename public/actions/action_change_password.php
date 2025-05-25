<?php
session_start();
require_once '../../private/database/db.php';

if (!isset($_SESSION['user_id'])) {
  die('Acesso negado.');
}

$userId = $_SESSION['user_id'];

// Obter password atual da BD
$stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
  die('Usuário não encontrado.');
}

$currentPassword = $_POST['current_password'];
$newPassword = $_POST['new_password'];

if (!password_verify($currentPassword, $user['password'])) {
  die('Password atual incorreta.');
}

$newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

$stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
$stmt->execute([$newPasswordHash, $userId]);

echo "Password alterada com sucesso.";
header('Location: ../pages/profile.php');
exit;
