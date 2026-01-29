<?php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM Users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getById($id) {
    $stmt = $this->pdo->prepare("SELECT userId, name, email, phone, address, role FROM Users WHERE userId = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


    public function addUser($name, $email, $phone, $address, $role, $password) {
    $sql = "INSERT INTO Users (name, email, phone, address, role, password, createdAt) 
            VALUES (?, ?, ?, ?, ?, ?, GETDATE())";

    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$name, $email, $phone, $address, $role, $password]);
    }

    public function getOrdersByUser($userId) {
    $stmt = $this->pdo->prepare("
        DECLARE @UserID INT = :userId;
        EXEC dbo.GetUserOrders @UserID = @UserID;
    ");
    $stmt->execute([':userId' => $userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function edit() {
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    if(!isset($_SESSION['user_id'])) {
        header('Location: index.php?controller=users&action=login');
        exit;
    }

    require_once __DIR__ . '/../models/User.php';
    $userModel = new User($this->pdo);
    $userId = $_SESSION['user_id'];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';

        $userModel->update($userId, $name, $email, $phone, $address);

        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['phone'] = $phone;
        $_SESSION['user']['address'] = $address;

        $successMessage = "Дані оновлено успішно.";
    }

    $user = $userModel->getById($userId);
    require_once __DIR__ . '/../views/users/profile.php';
}

 public function update($id, $name, $email, $phone, $address) {
        $stmt = $this->pdo->prepare("UPDATE Users SET name = ?, email = ?, phone = ?, address = ? WHERE userId = ?");
        return $stmt->execute([$name, $email, $phone, $address, $id]);
    }

    // Отримати всіх користувачів
public function getAllUsers() {
    $stmt = $this->pdo->prepare("SELECT userId, name, email, phone, address, role FROM Users ORDER BY userId ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Оновлення даних користувача адміністратором
public function updateAdmin($id, $name, $email, $phone, $address, $role) {
    $stmt = $this->pdo->prepare(
        "UPDATE Users SET name = ?, email = ?, phone = ?, address = ?, role = ? WHERE userId = ?"
    );
    return $stmt->execute([$name, $email, $phone, $address, $role, $id]);
}

public function deleteUser($userId) {
    try {
        $this->pdo->beginTransaction();

        // 1. Видалити OrderItems по замовленнях користувача
        $stmt = $this->pdo->prepare("
            DELETE OI
            FROM OrderItems OI
            INNER JOIN Orders O ON OI.orderID = O.orderID
            WHERE O.userID = ?
        ");
        $stmt->execute([$userId]);

        // 2. Видалити Orders користувача
        $stmt = $this->pdo->prepare("DELETE FROM Orders WHERE userID = ?");
        $stmt->execute([$userId]);

        // 3. Видалити Reviews користувача
        $stmt = $this->pdo->prepare("DELETE FROM Reviews WHERE userId = ?");
        $stmt->execute([$userId]);

        // 4. Видалити записи з Wishlist
        $stmt = $this->pdo->prepare("DELETE FROM Wishlist WHERE userId = ?");
        $stmt->execute([$userId]);

        // 5. Тепер можна видалити самого користувача
        $stmt = $this->pdo->prepare("DELETE FROM Users WHERE userId = ?");
        $stmt->execute([$userId]);

        $this->pdo->commit();
        return true;
    } catch (PDOException $e) {
        $this->pdo->rollBack();
        throw $e;
    }
}





}
