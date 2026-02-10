<?php
require_once __DIR__ . '/../models/Wishlist.php';

class WishlistController {
    private $wishlist;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->wishlist = new Wishlist($pdo);
    }

    public function view() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) die("Вхід потрібен");

        $wishlistItems = $this->wishlist->getUserWishlist($userId);
        require __DIR__ . '/../views/wishlist/view.php';
    }

    public function addAjax() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    header('Content-Type: application/json; charset=utf-8');

    $userId = $_SESSION['user_id'] ?? null;
    $productId = $_POST['productId'] ?? null;

    if (!$userId || !$productId) {
        echo json_encode(['success' => false, 'message' => 'Вхід потрібен або productId не вказано']);
        exit;
    }

    try {
        $added = $this->wishlist->add($userId, $productId);
        if ($added) {
            echo json_encode(['success' => true, 'message' => 'Товар додано у wishlist']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Товар вже у wishlist']);
        }
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Помилка сервера: ' . $e->getMessage()]);
    }

    exit;
}

    public function remove() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) die("Вхід потрібен");

        $productId = $_GET['productId'] ?? null;
        if (!$productId) die("ID товару не вказано");

        $this->wishlist->remove($userId, $productId);

        $back = $_SERVER['HTTP_REFERER'] ?? 'index.php?controller=products&action=list';
        header("Location: $back");
        exit;
    }
}
