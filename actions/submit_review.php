<?php
session_start();
require_once '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = intval($_POST['service_id']);
    $rating     = intval($_POST['rating']);
    $comment    = trim($_POST['comment']);

    if ($rating >= 1 && $rating <= 5 && isset($_SESSION['user_id'])) {
        $stmt = $db->prepare("
            INSERT INTO reviews (service_id, user_id, rating, comment, created_at)
            VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)
        ");
        $stmt->execute([$service_id, $_SESSION['user_id'], $rating, $comment]);

        header("Location: ../pages/view_service.php?id=$service_id&review=success");
        exit;
    }
}

// Caso algo falhe
header("Location: ../pages/view_service.php?id=$service_id&review=error");
exit;
?>
