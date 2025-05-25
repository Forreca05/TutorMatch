<?php
session_start();
require_once '../../private/database/db.php'; // conexão à base de dados
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

$userId = filter_var($_POST['id'], FILTER_VALIDATE_INT);
$stmt = $db->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$userId]);
header('Location: ../pages/manage_users.php');
exit;
