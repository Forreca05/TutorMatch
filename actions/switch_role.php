<?php
session_start();
require_once '../database/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$currentRole = $_SESSION['role'];
$newRole = $currentRole === 'freelancer' ? 'client' : 'freelancer';

// Atualiza a base de dados
$stmt = $db->prepare("UPDATE users SET role = ? WHERE id = ?");
$stmt->execute([$newRole, $_SESSION['user_id']]);

// Atualiza a sessão
$_SESSION['role'] = $newRole;

header('Location: ../index.php');
exit;
