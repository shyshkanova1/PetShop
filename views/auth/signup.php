<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>


<div class="container">
    <h3>Реєстрація нового користувача</h3>

    <?php if (isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="index.php?controller=users&action=signup">
        <label>Ім'я:</label>
        <input type="text" name="name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Телефон:</label>
        <input type="text" name="phone" required>

        <label>Адреса:</label>
        <input type="text" name="address" required>

        <label>Пароль:</label>
        <input type="password" name="password" required>

        <button type="submit">Зареєструватися</button>
    </form>

    <p>Вже є акаунт? <a href="index.php?controller=users&action=login">Увійти</a></p>
</div>
<style>
    .container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px 30px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
body form button {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}
    </style>