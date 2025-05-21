<?php
session_start();
require_once '../../private/database/db.php';

// Only allow POST from a logged-in client
if ($_SERVER['REQUEST_METHOD'] !== 'POST'
    || !isset($_SESSION['user_id'])
    || ($_SESSION['role'] ?? '') !== 'client'
) {
    header('Location: ../auth/login.php');
    exit;
}

$client_id     = $_SESSION['user_id'];
$order_id      = intval($_POST['order_id']     ?? 0);
$service_id    = intval($_POST['service_id']   ?? 0);
$freelancer_id = intval($_POST['freelancer_id']?? 0);

// Basic validation
if (!$order_id || !$service_id || !$freelancer_id) {
    header('Location: ../pages/payment_status.php?result=error');
    exit;
}

// (Optionally, you could verify that this order exists and belongs to you.
// But since you said “not that deep,” we'll skip it and just update.)

$stmt = $db->prepare("
    UPDATE orders
       SET status = 'Paid'
     WHERE id = ?
");
$stmt->execute([$order_id]);

// Redirect with success or error
if ($stmt->rowCount() > 0) {
    header('Location: ../pages/payment_status.php?result=success');
} else {
    header('Location: ../pages/payment_status.php?result=error');
}
exit;
