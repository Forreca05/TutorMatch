<?php
session_start();

if ($_SESSION['role'] == 'freelancer') {
    $_SESSION['role'] = 'client';
} else {
    $_SESSION['role'] = 'freelancer';
}

header('Location: ../index.php');
exit;
