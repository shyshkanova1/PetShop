<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/User.php';

class UsersController {
    private $userModel;
    private $pdo; // <-- це ключово

    public function __construct($pdo) {
        $this->pdo = $pdo;      // зберігаємо PDO
        $this->userModel = new User($pdo);
    }

    // === Реєстрація ===
    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $role = 'user';
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Додаємо користувача
            $this->userModel->addUser($name, $email, $phone, $address, $role, $password);

            // Отримуємо тільки що створеного користувача
            $user = $this->userModel->getByEmail($email);

            // Створюємо сесію
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $user['userId'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_name'] = $user['name'];

            // Редірект на профайл
            header('Location: index.php?controller=users&action=profile');
            exit;
        }

        require_once __DIR__ . '/../views/auth/signup.php';
    }

    // === Логін ===
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->getByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $user['userId'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['user_name'] = $user['name'];
                header('Location: index.php?controller=products&action=list');
                exit;
            } else {
                $error = 'Неправильний email або пароль';
            }
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }

    // === Вихід ===
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header('Location: index.php?controller=products&action=list');
        exit;
    }

    // === Профайл ===
    public function profile() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        // Отримуємо лише потрібні поля
        $user = $this->userModel->getById($_SESSION['user_id']);
    $orders = $this->userModel->getOrdersByUser($_SESSION['user_id']);
        require_once __DIR__ . '/../views/users/profile.php';
    }
     // === Історія замовлень ===
    public function orders() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        require_once __DIR__ . '/../models/Order.php';
        $orderModel = new Order($this->pdo);

        $userId = $_SESSION['user_id'];
        $orders = $orderModel->getOrdersByUser($userId);

        foreach ($orders as &$order) {
            $order['items'] = $orderModel->getOrderItems($order['orderID']);
        }

        require_once __DIR__ . '/../views/users/orders.php';
    }
    


    // Показати профіль та обробка редагування
    public function edit() {
        if(session_status() !== PHP_SESSION_ACTIVE) session_start();

        if(!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=users&action=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $userModel = new User($this->pdo);
        $successMessage = '';

        // Обробка POST-запиту (редагування даних)
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';

            $userModel->update($userId, $name, $email, $phone, $address);

            // Оновлюємо дані в сесії
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['user']['address'] = $address;

            $successMessage = "Дані оновлено успішно.";
        }

        $user = $userModel->getById($userId);

        // Підключаємо view
        require_once __DIR__ . '/../views/users/profile.php';
    }

    public function delete() {
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    if(!isset($_SESSION['user_id'])) {
        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    $userId = $_SESSION['user_id'];

    // Перевірка незавершених замовлень
    $stmt = $this->pdo->prepare(
        "SELECT COUNT(*) as cnt 
         FROM Orders 
         WHERE userId = :userId AND status NOT IN ('Complete', 'Cancelled')"
    );
    $stmt->execute([':userId' => $userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result['cnt'] > 0) {
        $_SESSION['error_message'] = "Ви не можете видалити акаунт, поки є незавершене замовлення.";
        header('Location: index.php?controller=users&action=profile');
        exit;
    }

    try {
        $this->pdo->beginTransaction();

        // 1️⃣ Видаляємо всі OrderItems замовлень користувача
        $stmtOrderIds = $this->pdo->prepare("SELECT orderID FROM Orders WHERE userId = :userId");
        $stmtOrderIds->execute([':userId' => $userId]);
        $orderIds = $stmtOrderIds->fetchAll(PDO::FETCH_COLUMN);

        if(!empty($orderIds)) {
            $inQuery = implode(',', array_fill(0, count($orderIds), '?'));
            $stmtOrderItems = $this->pdo->prepare("DELETE FROM OrderItems WHERE orderID IN ($inQuery)");
            $stmtOrderItems->execute($orderIds);
        }

        // 2️⃣ Видаляємо всі Orders користувача
        $stmtOrders = $this->pdo->prepare("DELETE FROM Orders WHERE userId = :userId");
        $stmtOrders->execute([':userId' => $userId]);

        // 3️⃣ Видаляємо коментарі
        $stmtComments = $this->pdo->prepare("DELETE FROM Reviews WHERE userId = :userId");
        $stmtComments->execute([':userId' => $userId]);

        // 4️⃣ Видаляємо wishlist
        $stmtWishlist = $this->pdo->prepare("DELETE FROM Wishlist WHERE userId = :userId");
        $stmtWishlist->execute([':userId' => $userId]);

        // 5️⃣ Видаляємо самого користувача
        $stmtUser = $this->pdo->prepare("DELETE FROM Users WHERE userId = :userId");
        $stmtUser->execute([':userId' => $userId]);

        $this->pdo->commit();

        // Чистимо сесію
        session_unset();
        session_destroy();

        header('Location: index.php?controller=products&action=list');
        exit;

    } catch (Exception $e) {
        $this->pdo->rollBack();
        die("Помилка видалення акаунту: " . $e->getMessage());
    }
}





}
