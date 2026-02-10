<?php
require_once __DIR__ . '/../core/ORM_init.php';

class Wishlist extends ORM {
    protected static $table = 'Wishlist';
    protected static $primaryKey = null;

    public $userId;
    public $productId;

    public function __construct($pdo) {
        parent::__construct($pdo);
    }

    public function add($userId, $productId) {
       
        if ($this->exists($userId, $productId)) {
            return false;
        }

        $sql = "INSERT INTO Wishlist (userId, productId) VALUES (:userId, :productId)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':userId' => $userId,
            ':productId' => $productId
        ]);
    }

    public function remove($userId, $productId) {
        $sql = "DELETE FROM Wishlist WHERE userId = :userId AND productId = :productId";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':userId' => $userId,
            ':productId' => $productId
        ]);
    }

    public function exists($userId, $productId) {
        $sql = "SELECT COUNT(*) as cnt FROM Wishlist WHERE userId = :userId AND productId = :productId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':userId' => $userId,
            ':productId' => $productId
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['cnt'] > 0;
    }

    public function getUserWishlist($userId) {
        $sql = "
            SELECT w.productId, p.name, p.price, p.imageUrl
            FROM Wishlist w
            JOIN Products p ON w.productId = p.productId
            WHERE w.userId = :userId
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);

        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = (object)$row;
        }
        return $results;
    }
}
