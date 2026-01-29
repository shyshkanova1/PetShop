<?php
require_once __DIR__ . '/../core/ORM_init.php';

class Review extends ORM {
    protected static $table = 'Reviews';
    protected static $primaryKey = 'reviewId';

    // Отримати всі відгуки для продукту з ім'ям користувача
    public static function getReviewsByProduct($pdo, $productId) {
        $sql = "
            SELECT r.*, u.name AS userName
            FROM Reviews r
            JOIN Users u ON r.userId = u.userId
            WHERE r.productId = :productId
            ORDER BY r.reviewId DESC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':productId' => $productId]);

        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = new static($pdo, $row);
        }
        return $results;
    }

    // Додати новий відгук
    public static function addReview($pdo, $productId, $userId, $rating, $comment) {
        $review = new static($pdo, [
            'productId' => $productId,
            'userId'    => $userId,
            'rating'    => $rating,
            'comment'   => $comment,
            'createdAt' => date('Y-m-d H:i:s')
        ]);
        return $review->save();
    }

    // Видалити відгук (soft delete)
    public static function removeReview($pdo, $reviewId, $userId) {
        $sql = "UPDATE Reviews SET isDeleted = 1 WHERE reviewId = :reviewId AND userId = :userId";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':reviewId' => $reviewId,
            ':userId'   => $userId
        ]);
    }
}
