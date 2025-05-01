<?php
session_start();
require_once '../database/db.php'; // se precisares da BD

$userId = $_SESSION['user_id'];

if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
    $uploadDir = '../uploads/';
    $fileName = basename($_FILES['profile_pic']['name']);
    $targetFile = $uploadDir . $userId . '_' . time() . '_' . $fileName;

    // Check if image
    $check = getimagesize($_FILES['profile_pic']['tmp_name']);
    if ($check === false) {
        die('O ficheiro não é uma imagem válida.');
    }

    // Move file
    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile)) {
        // Guarda o caminho na base de dados
        $stmt = $db->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
        $stmt->execute([$targetFile, $userId]);

        header('Location: ../pages/profile.php');
    } else {
        echo 'Erro ao fazer upload da imagem.';
    }
} else {
    echo 'Nenhuma imagem enviada ou ocorreu um erro.';
}
