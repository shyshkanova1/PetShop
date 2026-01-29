<?php
class Order {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Створює нове замовлення
     * @param int $userId
     * @param string $shippingAddress
     * @return int $orderId
     */
    public function createOrder($userId, $shippingAddress) {
        $sql = "
            INSERT INTO Orders (userId, totalAmount, status, createdAt, shippingAddress)
            VALUES (:userId, 0, 'new', GETDATE(), :shippingAddress)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':userId' => $userId,
            ':shippingAddress' => $shippingAddress
        ]);

        // Повертаємо ID щойно створеного замовлення
        return $this->pdo->lastInsertId();
    }

    /**
     * Отримати всі замовлення користувача
     */
    public function getOrdersByUser($userId) {
    $stmt = $this->pdo->prepare("
        DECLARE @UserID INT = :userId;
        EXEC dbo.GetUserOrders @UserID = @UserID;
    ");
    $stmt->execute([':userId' => $userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function cancel($orderID, $userID) { // дозволяємо скасувати тільки своє замовлення 
    $stmt = $this->pdo->prepare("UPDATE Orders SET status = 'Cancelled' WHERE orderID = ? AND userId = ?"); 
    return $stmt->execute([$orderID, $userID]); }

    /**
     * Отримати всі позиції конкретного замовлення
     */
    public function getOrderItems($orderId) {
        $stmt = $this->pdo->prepare("
            SELECT oi.orderItemID, oi.orderID, oi.productID, oi.quantity, oi.unitPrice, p.name AS productName
            FROM OrderItems oi
            JOIN Products p ON oi.productID = p.productId
            WHERE oi.orderID = :orderId
        ");
        $stmt->execute([':orderId' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllStatuses() {
    return ['new', 'processing', 'shipped', 'delivered', 'completed', 'cancelled'];
}
    
    public function getAllOrders($status = '', $userId = '') {
        $sql = "SELECT o.orderId, o.totalAmount, o.status, o.createdAt, o.shippingAddress,
                       u.name AS userName
                FROM Orders o
                JOIN Users u ON o.userId = u.userId
                WHERE 1=1";
        $params = [];

        if ($status !== '') {
            $sql .= " AND o.status = ?";
            $params[] = $status;
        }

        if ($userId !== '') {
            $sql .= " AND o.userId = ?";
            $params[] = $userId;
        }

        $sql .= " ORDER BY o.createdAt DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($orderId, $status) {
        $stmt = $this->pdo->prepare("UPDATE Orders SET status = ? WHERE orderId = ?");
        return $stmt->execute([$status, $orderId]);
    }

    public function deleteOrder($orderId) {
    try {
        $this->pdo->beginTransaction();

        // 1. Видалити всі OrderItems для цього замовлення
        $stmt = $this->pdo->prepare("DELETE FROM OrderItems WHERE orderID = ?");
        $stmt->execute([$orderId]);

        // 2. Видалити саме замовлення
        $stmt = $this->pdo->prepare("DELETE FROM Orders WHERE orderID = ?");
        $stmt->execute([$orderId]);

        $this->pdo->commit();
        return true;
    } catch (PDOException $e) {
        $this->pdo->rollBack();
        throw $e;
    }
}


    



}
