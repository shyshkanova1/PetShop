<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/Product.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die('Доступ заборонено');
}

$productModel = new Product($pdo);

// Отримати ID товару
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $productModel->delete($id);
}

header('Location: list.php');
exit;
