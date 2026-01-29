<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}  ?>

<div class="container">
    <h3>Увійти</h3>

    <?php if (isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="index.php?controller=users&action=login">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Пароль:</label>
        <input type="password" name="password" required>

        <button type="submit">Увійти</button>
    </form>

    <p>Ще немає акаунта? <a href="index.php?controller=users&action=signup">Зареєструватися</a></p>
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
form button {
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