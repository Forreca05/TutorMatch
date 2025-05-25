<?php
session_start();
require_once '../../private/database/db.php';

if (!isset($_SESSION['user_id'])) {
  die('Acesso negado.');
}

$userId = $_SESSION['user_id'];

// Sanitize input
$username = trim($_POST['username'] ?? '');
$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');

// Validate required fields
if (empty($username) || empty($email)) {
  die('Todos os campos obrigatórios devem ser preenchidos.');
}

$updateFields = ['username' => $username, 'name' => $name, 'email' => $email];
$profilePicUpdated = false;

// Handle profile picture upload if provided
if (
  isset($_FILES['profile_pic']) &&
  $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK &&
  is_uploaded_file($_FILES['profile_pic']['tmp_name'])
) {
  $uploadDir = '../uploads/';
  $originalName = basename($_FILES['profile_pic']['name']);
  $uniqueName = $userId . '_' . time() . '_' . $originalName;
  $targetFile = $uploadDir . $uniqueName;

  $imageInfo = getimagesize($_FILES['profile_pic']['tmp_name']);
  if ($imageInfo === false) {
    die('O ficheiro não é uma imagem válida.');
  }

  if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile)) {
    die('Erro ao carregar a imagem.');
  }

  $updateFields['profile_pic'] = $uniqueName;
  $profilePicUpdated = true;
}

// Build dynamic SQL query
$columns = array_keys($updateFields);
$placeholders = implode(' = ?, ', $columns) . ' = ?';
$values = array_values($updateFields);
$values[] = $userId;

$stmt = $db->prepare("UPDATE users SET $placeholders WHERE id = ?");
$stmt->execute($values);

// Update session values
$_SESSION['username']    = $username;
$_SESSION['name']        = $name;
$_SESSION['email']       = $email;
if ($profilePicUpdated) {
  $_SESSION['profile_pic'] = $uniqueName;
}

// Redirect to profile
header('Location: ../pages/profile.php');
exit;
