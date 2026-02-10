<?php 
if (session_status() !== PHP_SESSION_ACTIVE) session_start(); 

$editing = $_GET['edit'] ?? false;
?>

<style>
    .profile-container {
        max-width: 600px;
        margin: 40px auto;
        padding: 30px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        font-family: Arial, sans-serif;
    }

    .profile-container h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #333;
    }

    .profile-field {
        margin-bottom: 15px;
        font-size: 16px;
        color: #444;
    }

    .profile-field strong {
        display: inline-block;
        width: 120px;
        color: #222;
    }

    .success-message {
        background: #e6ffed;
        color: #207245;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 20px;
        text-align: center;
    }

    .profile-actions {
        margin-top: 30px;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-block;
        padding: 10px 18px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        cursor: pointer;
        border: none;
        transition: 0.2s;
    }

    .btn-primary {
        background: #4f46e5;
        color: #fff;
    }

    .btn-primary:hover {
        background: #4338ca;
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #111827;
    }

    .btn-secondary:hover {
        background: #d1d5db;
    }

    .edit-form label {
        display: block;
        margin-bottom: 15px;
        font-size: 14px;
        color: #374151;
    }

    .edit-form input {
        width: 100%;
        padding: 8px 10px;
        margin-top: 5px;
        border-radius: 6px;
        border: 1px solid #d1d5db;
        font-size: 14px;
    }

    .edit-form-actions {
        margin-top: 25px;
        display: flex;
        gap: 15px;
    }
</style>

<div class="profile-container">
    <h2>Особистий кабінет</h2>

    <?php if (!empty($successMessage)): ?>
        <div class="success-message" id="successMessage">
            <?= htmlspecialchars($successMessage) ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error_message'])): ?>
        <div class="error-message" id="errorMessage">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if (!$editing): ?>
        <div class="profile-field">
            <strong>Ім'я:</strong> <?= htmlspecialchars($user['name']) ?>
        </div>
        <div class="profile-field">
            <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?>
        </div>
        <div class="profile-field">
            <strong>Телефон:</strong> <?= htmlspecialchars($user['phone']) ?>
        </div>
        <div class="profile-field">
            <strong>Адреса:</strong> <?= htmlspecialchars($user['address']) ?>
        </div>

         <div class="profile-actions">
            <form>
            <a href="index.php?controller=users&action=edit&edit=1" class="btn btn-primary">
                Змінити дані
            </a>
             <?php if ($user['role'] !== 'admin'): ?>
            <a href="index.php?controller=users&action=orders" class="btn btn-secondary">
                Моя історія замовлень
            </a>
             <?php endif; ?>
    </form>
                <form action="index.php?controller=users&action=delete" method="post" style="display:inline;">
        <button type="submit" class="btn btn-primary" 
                onclick="return confirm('Ви впевнені, що хочете видалити акаунт? Ця дія незворотна!');">
            Видалити акаунт
        </button>
    </form>
        </div>

    <?php else: ?>
        <form class="edit-form" action="index.php?controller=users&action=edit" method="post">
            <label>
                Ім'я
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            </label>

            <label>
                Email
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </label>

            <label>
                Телефон
                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
            </label>

            <label>
                Адреса
                <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>">
            </label>

            <div class="edit-form-actions">
                <button type="submit" class="btn btn-primary">Зберегти</button>
                <a href="index.php?controller=users&action=edit" class="btn btn-secondary">Скасувати</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
window.addEventListener('DOMContentLoaded', () => {
    const success = document.getElementById('successMessage');
    if (success) {
        setTimeout(() => {
            success.style.opacity = '0';
            setTimeout(() => success.remove(), 500); 
        }, 4000);
    }

    const error = document.getElementById('errorMessage');
    if (error) {
        setTimeout(() => {
            error.style.opacity = '0';
            setTimeout(() => error.remove(), 500);
        }, 5000);
    }
});
</script>