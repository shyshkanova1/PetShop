<style>
/* Загальні стилі для адміністративних форм */
.admin-form {
    max-width: 600px;
    margin: 20px auto;
    font-family: Arial, sans-serif;
    font-size: 14px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
}
.h1 {
    text-align: center; 
}

.admin-form h1 {
    font-size: 22px;
    margin-bottom: 20px;
    color: #333;
    text-align: center; /* Центруємо заголовок */
}

.admin-form div {
    margin-bottom: 15px;
}

.admin-form label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.admin-form input[type="text"],
.admin-form input[type="email"],
.admin-form select {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.admin-form .form-buttons {
    text-align: center; /* Центруємо кнопки */
}

.admin-form button,
.admin-form .cancel-btn {
    display: inline-block;
    width: 120px; /* Однакова ширина для всіх кнопок */
    padding: 10px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    margin: 5px;
    transition: background-color 0.2s, color 0.2s;
    text-align: center;
    text-decoration: none; /* Для посилання */
}

.admin-form button {
    background-color: #4CAF50;
    color: white;
}

.admin-form button:hover {
    background-color: #45a049;
}

.admin-form .cancel-btn {
    background-color: #f44336;
    color: white;
}

.admin-form .cancel-btn:hover {
    background-color: #d32f2f;
}

.admin-form .success-message {
    color: green;
    font-weight: bold;
    margin-bottom: 15px;
    text-align: center;
}
</style>

<h1 class="h1">Редагування користувача</h1>

<?php if(!empty($successMessage)): ?>
    <div class="success-message"><?= htmlspecialchars($successMessage) ?></div>
<?php endif; ?>

<form class="admin-form" action="index.php?controller=adminUsers&action=edit&id=<?= $user['userId'] ?>" method="post">
    <div>
        <label for="name">Ім'я:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>
    <div>
        <label for="phone">Телефон:</label>
        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
    </div>
    <div>
        <label for="address">Адреса:</label>
        <input type="text" id="address" name="address" value="<?= htmlspecialchars($user['address']) ?>">
    </div>
    <div>
        <label for="role">Роль:</label>
        <select id="role" name="role">
            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Користувач</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Адміністратор</option>
        </select>
    </div>
    <div class="form-buttons">
        <button type="submit">Зберегти</button>
        <a href="index.php?controller=adminUsers&action=list" class="cancel-btn">Скасувати</a>
    </div>
</form>
