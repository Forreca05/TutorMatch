<?php
// File: ../actions/create_service_action.php
session_start();
require_once '../../private/database/db.php';
require_once(__DIR__ . '/../../private/utils/csrf.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf_token($_POST['csrf_token'])) {
    $userId = $_SESSION['user_id'];
    $title = htmlspecialchars($_POST['title'] ?? '');
    $category_id = htmlspecialchars($_POST['category_id'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    $price = htmlspecialchars($_POST['price'] ?? '');
    $delivery_time = htmlspecialchars($_POST['delivery_time'] ?? '');

    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = '..//uploads/';
        $filename = basename($_FILES['image']['name']);
        $targetFile = $uploadDir . time() . '_' . $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
        $imagePath = $targetFile;
    }

    $stmt = $db->prepare("INSERT INTO services (user_id, category_id, title, description, price, delivery_time, image_path, created_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, DATETIME('now'))");
    $stmt->execute([$userId, $category_id, $title, $description, $price, $delivery_time, $imagePath]);
    header('Location: ../pages/my_services.php');
    exit();
}
else {
    header('Location: ../pages/create_service.php?error=invalid_request');
    exit();
}
?>