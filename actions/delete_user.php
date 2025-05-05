<?php
session_start();
require_once '../database/db.php'; // conexão à base de dados

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);

    header('Location: ../pages/manage_users.php');
    exit;
} else {
    echo "ID do utilizador não especificado.";
}