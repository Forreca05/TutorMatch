<?php
session_start();
require_once '../../private/database/db.php'; // inclui a conexão à base de dados
require_once(__DIR__ . '/../../private/utils/csrf.php');


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/login.php');
    exit();
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
