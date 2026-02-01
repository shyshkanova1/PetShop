<?php
require_once __DIR__ . '/../core/ORM_init.php';

class Product extends ORM {
    protected static $table = 'Products';
    protected static $primaryKey = 'productId';

    public function __construct($pdo, $attributes = []) {
        parent::__construct($pdo, $attributes);
    }

    /* =====================
       Отримати всі товари
    ====================== */
    public function getAll($filterName = '', $categoryId = null) {
        $sql = "
            SELECT p.*, c.name AS categoryName
            FROM Products p
            LEFT JOIN Categories c ON p.categoryID = c.categoryId
            WHERE p.isDeleted = 0
        ";
        $params = [];

        if ($filterName) {
            $sql .= " AND p.name LIKE ?";
            $params[] = "%$filterName%";
        }

        if ($categoryId) {
            $sql .= " AND p.categoryID = ?";
            $params[] = $categoryId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================
       Отримати товар по ID
    ====================== */
    public function getById($id, $includeDeleted = false) {
        $sql = "
            SELECT p.*, c.name AS categoryName, c.description AS categoryDescription
            FROM Products p
            LEFT JOIN Categories c ON p.categoryID = c.categoryId
            WHERE p.productId = :id
        ";
        if (!$includeDeleted) $sql .= " AND p.isDeleted = 0";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =====================
       Середній рейтинг
    ====================== */
    public function getAverageRating($productId) {
        $stmt = $this->pdo->prepare("SELECT dbo.AvgRating(:productId) AS averageRating");
        $stmt->execute([':productId' => $productId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['averageRating'] ?? 0;
    }

    /* =====================
       Товари по категорії
    ====================== */
    public function getByCategory($categoryId) {
        $sql = "
            SELECT p.*, c.name AS categoryName
            FROM Products p
            JOIN Categories c ON p.categoryID = c.categoryId
            WHERE p.categoryID = ? AND p.isDeleted = 0
            ORDER BY p.createdAt DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================
       Wishlist
    ====================== */
    public function isInUserWishlist($productId, $userId) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM Wishlist 
            WHERE userId = :userId AND productId = :productId
        ");
        $stmt->execute([':userId'=>$userId, ':productId'=>$productId]);
        return $stmt->fetchColumn() > 0;
    }

    public function addToWishlist($userId, $productId) {
        $stmt = $this->pdo->prepare("
            INSERT INTO Wishlist (userId, productId)
            VALUES (:userId, :productId)
        ");
        return $stmt->execute([':userId'=>$userId, ':productId'=>$productId]);
    }

    public function removeFromWishlist($userId, $productId) {
        $stmt = $this->pdo->prepare("
            DELETE FROM Wishlist 
            WHERE userId = :userId AND productId = :productId
        ");
        return $stmt->execute([':userId'=>$userId, ':productId'=>$productId]);
    }

    /* =====================
       Повертає об’єкт Product за ID
    ====================== */
    public function getProductObject($id) {
        $sql = "SELECT * FROM Products WHERE productId = :id AND isDeleted = 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Product($this->pdo, $row) : null;
    }
}
