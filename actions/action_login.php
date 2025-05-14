<?php
session_start();
require_once '../database/db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();


if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['profile_pic'] = $user['profile_pic']; 
    $_SESSION['name'] = $user['name']; // Adicionando o nome do usuário à sessão
    $_SESSION['email'] = $user['email']; // Adicionando o email do usuário à sessão
    header('Location: ../index.php');
} else {
    header('Location: ../pages/login.php?error=Login inválido');
    exit;
}
