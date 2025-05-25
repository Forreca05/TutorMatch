<?php
require_once '../../private/database/db.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // encriptar password

try {
  $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
  $stmt->execute([$username, $email, $password]);

  header('Location: ../pages/login.php');
} catch (PDOException $e) {
  die('Erro no registo: ' . $e->getMessage());
}
