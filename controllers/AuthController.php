<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db.php';

class AuthController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../views/auth/login.php';
            return;
        }

        $stmt = $this->pdo->prepare("SELECT * FROM Users WHERE email=?");
        $stmt->execute([$_POST['email']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || $_POST['password'] !== $user['password']) {
            $_SESSION['error_message'] = "Невірний email або пароль";
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $_SESSION['user'] = [
            'id' => $user['userId'],
            'name' => $user['name'],
            'role' => $user['role']
        ];

        header('Location: index.php');
        exit;
    }

    public function logout() {
        session_destroy();
        header('Location: index.php');
        exit;
    }
}


