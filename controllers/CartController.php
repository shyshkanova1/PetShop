<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/OrderItem.php';

class CartController {
    private $productModel;
    private $orderModel;
    private $orderItemModel;
    private $userModel;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->productModel = new Product($pdo);
        $this->orderModel = new Order($pdo);
        $this->orderItemModel = new OrderItem($pdo);
        $this->userModel = new User($pdo);
    }

    public function add() {
        session_start();
        $productId = $_GET['productId'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;

        if (!$productId) {
            header('Location: index.php?controller=products&action=list');
            exit;
        }
        $product = $this->productModel->getById($productId);
        if (!$product) {
            die("Товар не знайдено");
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'productId' => $product['productId'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity
            ];
        }
        header('Location: index.php?controller=cart&action=view');
        exit;
    }

    
    public function view() {
        $cart = $_SESSION['cart'] ?? [];
        require_once __DIR__ . '/../views/cart/view.php';
    }

    public function remove() {
        session_start();
        $productId = $_GET['productId'] ?? null;
        if ($productId && isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
        header('Location: index.php?controller=cart&action=view');
        exit;
    }

public function update()
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {

        $productId = (int)($_POST['productId'] ?? 0);
        $quantity  = (int)($_POST['quantity'] ?? 1);

        if ($productId && isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] = max(1, $quantity);
            
            $item = $_SESSION['cart'][$productId];
            $itemTotal = $item['price'] * $item['quantity'];

            $total = 0;
            foreach ($_SESSION['cart'] as $cartItem) {
                $total += $cartItem['price'] * $cartItem['quantity'];
            }

            echo json_encode([
                'success' => true,
                'itemTotal' => number_format($itemTotal, 2),
                'total' => number_format($total, 2)
            ]);
            return;
        }

        echo json_encode(['success' => false, 'message' => 'Товар не знайдено']);
        return;
    }

    if (isset($_POST['quantities']) && is_array($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $productId => $qty) {
            $productId = (int)$productId;
            $qty = max(1, (int)$qty);
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity'] = $qty;
            }
        }
    }

    header('Location: index.php?controller=cart&action=view');
    exit;
}

public function checkout() {
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    $userId = $_SESSION['user_id'];
    $cart = $_SESSION['cart'] ?? [];

    if (empty($cart)) {
        die("Кошик порожній");
    }

    $user = $this->userModel->getById($userId);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        require_once __DIR__ . '/../views/cart/checkout.php';
        return;
    }

    $shippingAddress = $_POST['shipping_address'] ?? $user['address'];
    $name = $_POST['name'] ?? $user['name'];
    $phone = $_POST['phone'] ?? $user['phone'];

    try {
        $this->pdo->beginTransaction();

        $orderId = $this->orderModel->createOrder($userId, $shippingAddress);

        $totalAmount = 0;

        foreach ($cart as $item) {
            $productId = (int)$item['productId'];
            $quantity = (int)$item['quantity'];

            $product = $this->productModel->getById($productId);
            if (!$product) {
                throw new Exception("Товар із ID {$productId} більше не існує");
            }

            if ($product['stock'] < $quantity) {
                throw new Exception("INSUFFICIENT_STOCK: {$product['name']}");
            }

            $this->orderItemModel->addOrderItem(
                $orderId,
                $productId,
                $quantity,
                $product['price']
            );

            $stmt = $this->pdo->prepare("
                UPDATE Products SET stock = stock - :quantity WHERE productId = :productId
            ");
            $stmt->execute([
                ':quantity' => $quantity,
                ':productId' => $productId
            ]);

            $totalAmount += $product['price'] * $quantity;
        }

        $stmtTotal = $this->pdo->prepare("
            UPDATE Orders SET totalAmount = :total WHERE orderId = :orderId
        ");
        $stmtTotal->execute([
            ':total' => $totalAmount,
            ':orderId' => $orderId
        ]);

        unset($_SESSION['cart']);

        $this->pdo->commit();

        $orderedItems = $this->orderItemModel->getItemsByOrderId($orderId);

        require_once __DIR__ . '/../views/cart/thankyou.php';
        return;

    } catch (Exception $e) {
        $this->pdo->rollBack();

        $msg = $e->getMessage();

        if (str_contains($msg, "INSUFFICIENT_STOCK")) {
            require_once __DIR__ . '/../views/cart/insufficient_stock.php';
            return;
        }

    if (str_contains($msg, "Неможливо оформити замовлення") 
        || str_contains($msg, "незавершене замовлення")) {
        require_once __DIR__ . '/../views/cart/order_incomplete.php';
        return;
    }

        die("Помилка оформлення замовлення: " . $msg);
    }
}

}