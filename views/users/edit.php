<style>
.admin-form {
    max-width: 620px;
    margin: 30px auto;
    font-family: Arial, sans-serif;
    font-size: 14px;
    padding: 25px 30px;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.h1 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 22px;
    color: #333;
}

.admin-form div {
    margin-bottom: 16px;
}

.admin-form label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #444;
}

.admin-form input[type="text"],
.admin-form input[type="email"],
.admin-form select {
    width: 100%;
    padding: 10px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    box-sizing: border-box;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.admin-form input:focus,
.admin-form select:focus {
    outline: none;
    border-color: #4CAF50;
    box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
}

.form-buttons {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 25px;
}

.form-buttons button,
.form-buttons a {
    padding: 10px 18px;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
    transition: background-color 0.2s, transform 0.1s;
}

.form-buttons button {
    background-color: #4CAF50;
    color: #fff;
    border: none;
}

.form-buttons button:hover {
    background-color: #43a047;
    transform: translateY(-1px);
}

.cancel-btn {
    background-color: #f44336;
    color: #fff;
}

.cancel-btn:hover {
    background-color: #d32f2f;
    transform: translateY(-1px);
}

.back-btn {
    background-color: #607d8b;
    color: #fff;
}

.back-btn:hover {
    background-color: #546e7a;
    transform: translateY(-1px);
}

.success-alert {
    max-width: 620px;
    margin: 20px auto;
    padding: 12px 18px;
    background-color: #e8f5e9;
    border: 1px solid #4CAF50;
    color: #2e7d32;
    border-radius: 6px;
    font-weight: 600;
    text-align: center;
    animation: fadeIn 0.4s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>

<h1 class="h1">Редагування користувача</h1>

<?php if (!empty($successMessage)): ?>
    <div id="successAlert" class="success-alert">
        <?= htmlspecialchars($successMessage) ?>
    </div>
<?php endif; ?>

<form class="admin-form"
      action="index.php?controller=adminUsers&action=edit&id=<?= $user['userId'] ?>"
      method="post">

    <div>
        <label for="name">Ім'я:</label>
        <input type="text" id="name" name="name"
               value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>

    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"
               value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>

    <div>
        <label for="phone">Телефон:</label>
        <input type="text" id="phone" name="phone"
               value="<?= htmlspecialchars($user['phone']) ?>">
    </div>

    <div>
        <label for="address">Адреса:</label>
        <input type="text" id="address" name="address"
               value="<?= htmlspecialchars($user['address']) ?>">
    </div>

    <div>
        <label for="role">Роль:</label>
        <select id="role" name="role">
            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>
                Користувач
            </option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>
                Адміністратор
            </option>
        </select>
    </div>

    <div class="form-buttons">
        <button type="submit">Зберегти</button>

        <a href="index.php?controller=adminUsers&action=list" class="cancel-btn">
            Скасувати
        </a>

        <a href="index.php?controller=adminUsers&action=list" class="back-btn">
            До списку користувачів
        </a>
    </div>
</form>

<script>
setTimeout(() => {
    const alert = document.getElementById('successAlert');
    if (alert) {
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 400);
    }
}, 3000);
</script>
