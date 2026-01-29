<?php
require_once __DIR__ . '/../models/User.php';

class AdminUsersController {
    private $userModel;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->userModel = new User($pdo);
    }

    // === Список всіх користувачів для адміна ===
    public function list() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Перевірка ролі
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=users&action=login');
            exit;
        }

        // Отримуємо всіх користувачів
        $users = $this->userModel->getAllUsers();

        // Підключаємо view
        require_once __DIR__ . '/../views/users/list.php';
    }

    // === Редагування користувача адміністратором ===
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
            $role = $_POST['role'] ?? 'user'; // лише "user" або "admin"

            // Викликаємо новий метод для оновлення користувача адміністратором
            $this->userModel->updateAdmin($userId, $name, $email, $phone, $address, $role);


            $successMessage = "Дані користувача успішно оновлено";
            $user = $this->userModel->getById($userId); // оновлюємо дані для view
        }

        require_once __DIR__ . '/../views/users/edit.php';
    }

    // === Видалення користувача (за потребою) ===
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
