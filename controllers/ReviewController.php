<?php
require_once __DIR__ . '/../models/Review.php';

class ReviewController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) session_start();

            $productId = $_POST['productId'] ?? null;
            $comment   = $_POST['comment'] ?? '';
            $rating    = $_POST['rating'] ?? 1;
            $userId    = $_SESSION['user_id'] ?? null;

            if (!$productId || !$userId) die('Немає продукту або користувача для додавання відгуку');

            Review::addReview($this->pdo, $productId, $userId, $rating, $comment);

            header("Location: index.php?controller=products&action=view&id={$productId}");
            exit;
        }
    }

    public function remove() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (session_status() === PHP_SESSION_NONE) session_start();

            $reviewId  = $_GET['reviewId'] ?? null;
            $productId = $_GET['productId'] ?? null;
            $userId    = $_SESSION['user_id'] ?? null;

            if ($reviewId && $userId) {
                Review::removeReview($this->pdo, $reviewId, $userId);
            }

            header("Location: index.php?controller=products&action=view&id={$productId}");
            exit;
        }
    }
}
