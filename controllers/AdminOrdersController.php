<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/User.php';

class AdminOrdersController {
    private $pdo;
    private $orderModel;
    private $userModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->orderModel = new Order($pdo);
        $this->userModel = new User($pdo);
    }

// ================================
// Список замовлень (БЕЗ зміни БД)
// ================================
public function list() {
    if (session_status() === PHP_SESSION_NONE) session_start();

    // Перевірка ролі адміністратора
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: index.php?controller=users&action=login');
        exit;
    }

    $status = $_GET['status'] ?? '';
    $userId = $_GET['userId'] ?? '';

    // Отримуємо всі замовлення з фільтрацією
    $orders = $this->orderModel->getAllOrders($status, $userId);

    // Отримуємо всі користувачі для фільтра
    $users = $this->userModel->getAllUsers();

    // Отримуємо всі позиції кожного замовлення
    foreach ($orders as &$order) {
        $orderId = $order['orderId'];
        $order['items'] = $this->orderModel->getOrderItems($orderId);
    }
    unset($order);

    // Отримуємо усі можливі статуси для select
    $statuses = $this->orderModel->getAllStatuses();

    // Підключаємо view
    
    
    require_once __DIR__ . '/../views/orders/list.php';
}


    // ==========================================
    // Перерахунок сум замовлень (ПРОЦЕДУРА + TX)
    // ==========================================
    public function recalcTotals() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=users&action=login');
            exit;
        }

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("
                DECLARE @UpdatedOrdersCount INT;
                EXEC dbo.UpdateOrderStats @UpdatedOrdersCount OUTPUT;
                SELECT @UpdatedOrdersCount AS UpdatedOrdersCount;
            ");

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $updatedOrdersCount = $result['UpdatedOrdersCount'] ?? 0;

            $this->pdo->commit();

            $_SESSION['success_message'] =
                'Перераховано замовлень: ' . $updatedOrdersCount;

        } catch (PDOException $e) {
            $this->pdo->rollBack();

            $_SESSION['error_message'] =
                'Помилка перерахунку замовлень';

            error_log($e->getMessage());
        }

        header('Location: index.php?controller=adminOrders&action=list');
        exit;
    }

    // ================================
    // Оновлення статусу замовлення
    // ================================
    public function updateStatus() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=users&action=login');
            exit;
        }

        $orderId = $_POST['orderId'];
        $status  = $_POST['status'];

        $this->orderModel->updateStatus($orderId, $status);

        header('Location: index.php?controller=adminOrders&action=list');
        exit;
    }

    // ================================
    // Видалення замовлення
    // ================================
    public function delete() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=users&action=login');
            exit;
        }

        $orderId = $_GET['id'];
        $this->orderModel->deleteOrder($orderId);

        header('Location: index.php?controller=adminOrders&action=list');
        exit;
    }
}
