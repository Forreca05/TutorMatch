<?php
session_start();
require_once '../database/db.php';

$login = $_POST['username']; // Can be username or email
$password = $_POST['password'];

$stmt = $db->prepare("SELECT * FROM users WHERE username = ? Or email = ? LIMIT 1");
$stmt->execute([$login, $login]);
$userName = $stmt->fetch();

if ($userName && password_verify($password, $userName['password'])) {
    $_SESSION['user_id'] = $userName['id'];
    $_SESSION['username'] = $userName['username'];
    $_SESSION['role'] = $userName['role'];
    $_SESSION['profile_pic'] = $userName['profile_pic']; 
    $_SESSION['name'] = $userName['name']; // Adicionando o nome do usuário à sessão
    $_SESSION['email'] = $userName['email']; // Adicionando o email do usuário à sessão
    header('Location: ../index.php');
}
else {
    header('Location: ../pages/login.php?error=Login inválido');
    exit;
}
