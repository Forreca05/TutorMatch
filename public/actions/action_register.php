<?php
require_once '../../private/database/db.php';
require_once(__DIR__ . '/../../private/utils/csrf.php');

if (!verify_csrf_token($_POST['csrf_token'])) {
    header('Location: ../pages/register.php?error=invalid_token');
    exit();
}

$username = htmlspecialchars($_POST['username']);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // encriptar password

try {
    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $password]);

    header('Location: ../pages/login.php');
} catch (PDOException $e) {
    die('Erro no registo: ' . $e->getMessage());
}
