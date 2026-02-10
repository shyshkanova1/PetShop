<?php
require_once __DIR__ . '/../models/User.php';

class AdminUsersController {
    private $userModel;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->userModel = new User($pdo);
    }

    public function list() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=users&action=login');
            exit;
        }

        $users = $this->userModel->getAllUsers();

        require_once __DIR__ . '/../views/users/list.php';
    }

    public function edit() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=users&action=login');
            exit;
        }

        $userId = $_GET['id'] ?? null;
        if (!$userId) {
            die('Не вказано ID користувача');
        }

        $user = $this->userModel->getById($userId);
        $successMessage = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $role = $_POST['role'] ?? 'user'; 

            $this->userModel->updateAdmin($userId, $name, $email, $phone, $address, $role);

            $successMessage = "Дані користувача успішно оновлено";
            $user = $this->userModel->getById($userId); 
        }

        require_once __DIR__ . '/../views/users/edit.php';
    }

    public function delete() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=users&action=login');
            exit;
        }

        $userId = $_GET['id'] ?? null;
        if (!$userId) {
            die('Не вказано ID користувача');
        }

        $this->userModel->deleteUser($userId);
        header('Location: index.php?controller=adminUsers&action=list');
        exit;
    }
}
