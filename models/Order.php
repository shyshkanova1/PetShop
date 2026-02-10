<?php
class Order {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

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

        return $this->pdo->lastInsertId();
    }

    public function getOrdersByUser($userId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                o.orderID AS orderId, o.status, o.totalAmount, o.createdAt, o.shippingAddress,
                oi.orderItemID, oi.productID, oi.quantity, oi.unitPrice,
                p.name AS productName
            FROM Orders o
            LEFT JOIN OrderItems oi ON o.orderID = oi.orderID
            LEFT JOIN Products p ON oi.productID = p.productId
            WHERE o.userID = :userId
            ORDER BY o.createdAt DESC, o.orderID DESC, oi.orderItemID ASC
        ");
        $stmt->execute([':userId' => $userId]);
        $rawOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $orders = [];

        foreach ($rawOrders as $row) {
            $orderId = $row['orderId'];

            if (!isset($orders[$orderId])) {
                $orders[$orderId] = [
                    'orderId' => $row['orderId'],
                    'status' => $row['status'],
                    'totalAmount' => $row['totalAmount'],
                    'createdAt' => $row['createdAt'],
                    'shippingAddress' => $row['shippingAddress'],
                    'items' => []
                ];
            }

            if ($row['orderItemID'] !== null) {
                $orders[$orderId]['items'][] = [
                    'orderItemID' => $row['orderItemID'],
                    'productID' => $row['productID'],
                    'productName' => $row['productName'],
                    'quantity' => $row['quantity'],
                    'unitPrice' => $row['unitPrice']
                ];
            }
        }

        return array_values($orders);
    }

    public function cancel($orderID, $userID) { 
    $stmt = $this->pdo->prepare("UPDATE Orders SET status = 'cancelled' WHERE orderID = ? AND userId = ?"); 
    return $stmt->execute([$orderID, $userID]); }

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
    $status = strtolower($status);

    if (!in_array($status, $this->getAllStatuses())) {
        throw new Exception("Invalid status: $status");
    }

    $stmt = $this->pdo->prepare(
        "UPDATE Orders SET status = ? WHERE orderId = ?"
    );
    $stmt->execute([$status, $orderId]);

    if ($stmt->rowCount() === 0) {
        error_log("Order status NOT updated. orderId=$orderId");
    }

    return true;
}

    public function deleteOrder($orderId) {
    try {
        $this->pdo->beginTransaction();

        $stmt = $this->pdo->prepare("DELETE FROM OrderItems WHERE orderID = ?");
        $stmt->execute([$orderId]);

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
