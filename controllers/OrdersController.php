<?php
require_once __DIR__ . '/../models/Order.php';

class OrdersController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function cancel() {
        if(session_status() !== PHP_SESSION_ACTIVE) session_start();

        if(!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=users&action=login');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderID = $_POST['orderId'];
            $userID = $_SESSION['user_id'];

            $orderModel = new Order($this->pdo);
            $orderModel->cancel($orderID, $userID);
        }

        header('Location: index.php?controller=users&action=orders');
        exit;
    }
}
