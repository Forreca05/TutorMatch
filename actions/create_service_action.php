<?php
// File: ../actions/create_service_action.php
session_start();
require_once '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $delivery_time = $_POST['delivery_time'];

    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = '../uploads/';
        $filename = basename($_FILES['image']['name']);
        $targetFile = $uploadDir . time() . '_' . $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
        $imagePath = $targetFile;
    }

    $stmt = $db->prepare("INSERT INTO services (user_id, category_id, title, description, price, delivery_time, image_path, created_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, DATETIME('now'))");
    $stmt->execute([$userId, $category_id, $title, $description, $price, $delivery_time, $imagePath]);
    header('Location: ../pages/my_services.php');
}
?>