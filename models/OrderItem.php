<?php
class OrderItem {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function addOrderItem($orderId, $productId, $quantity, $unitPrice) {
        $stmt = $this->pdo->prepare("
            INSERT INTO OrderItems (orderID, productID, quantity, unitPrice)
            VALUES (:orderID, :productID, :quantity, :unitPrice)
        ");
        $stmt->execute([
            ':orderID' => $orderId,
            ':productID' => $productId,
            ':quantity' => $quantity,
            ':unitPrice' => $unitPrice
        ]);
    }
 
    public function getItemsByOrderId($orderId) {
        $stmt = $this->pdo->prepare("
            SELECT oi.*, p.name AS productName
            FROM OrderItems oi
            JOIN Products p ON oi.productID = p.productId
            WHERE oi.orderID = :orderId
        ");
        $stmt->execute([':orderId' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
