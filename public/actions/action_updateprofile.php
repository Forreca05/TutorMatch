<?php
session_start();
require_once '../../private/database/db.php';
require_once(__DIR__ . '/../../private/utils/csrf.php');

if (!isset($_SESSION['user_id'])) {
    die('Acesso negado.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !verify_csrf_token($_POST['csrf_token'])) {
    die('Nao foi possivel completar esta operação.');
}

$userId = $_SESSION['user_id'];

// Atualizar username e name
$username = htmlspecialchars($_POST['username'] ?? '');
$name = htmlspecialchars($_POST['name'] ?? '');
// Atualizar foto (se enviada)
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
    $uploadDir = '../uploads/';
    $fileName = basename($_FILES['profile_pic']['name']);
    $uniqueName = $userId . '_' . time() . '_' . $fileName;
    $targetFile = $uploadDir . $uniqueName;

    $check = getimagesize($_FILES['profile_pic']['tmp_name']);
    if ($check === false) {
        die('O ficheiro não é uma imagem válida.');
    }

    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile)) {
        // Atualiza TUDO incluindo a imagem
        $stmt = $db->prepare("UPDATE users SET username = ?, name = ?, profile_pic = ? WHERE id = ?");
        $stmt->execute([$username, $name, $uniqueName, $userId]);
    } else {
        die('Erro ao carregar a imagem.');
    }
} else {
    // Só atualiza username e name
    $stmt = $db->prepare("UPDATE users SET username = ?, name = ? WHERE id = ?");
    $stmt->execute([$username, $name, $userId]);
}

// Atualizar a sessão (caso mudem o username)
$_SESSION['username'] = $username;
$_SESSION['name'] = $name;
$_SESSION['profile_pic'] = $uniqueName ?? $_SESSION['profile_pic']; // Atualiza a imagem na sessão se foi alterada

header('Location: ../pages/profile.php');
exit;
