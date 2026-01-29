<?php
// Перевірка, чи є змінні з контролера
if (!isset($user, $orderedItems, $totalAmount)) {
    die("Неможливо відобразити сторінку подяки. Дані відсутні.");
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9fafb;
        margin: 0;
        padding: 0;
    }

    .thankyou-container {
        max-width: 700px;
        margin: 50px auto;
        padding: 40px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        text-align: center;
    }

    .thankyou-container h1 {
        color: #2f855a; /* зелений заголовок */
        margin-bottom: 20px;
        font-size: 28px;
    }

    .thankyou-container p {
        font-size: 16px;
        color: #2d3748;
        margin: 8px 0;
    }

    .ordered-items {
        margin-top: 30px;
    }

    .ordered-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 15px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 15px;
    }

    .ordered-item.header {
        font-weight: bold;
        background-color: #f5f5f5;
        border-bottom: 2px solid #cbd5e1;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        font-weight: bold;
        font-size: 16px;
    }

    .btn-home {
        display: inline-block;
        margin-top: 30px;
        padding: 12px 30px;
        border-radius: 6px;
        background-color: #4CAF50; /* зелений як на кошику */
        color: #fff;
        text-decoration: none;
        font-size: 16px;
        transition: background-color 0.2s;
    }

    .btn-home:hover {
        background-color: #43a047;
    }
</style>

<div class="thankyou-container">
    <h1>Дякуємо за ваше замовлення!</h1>
    <p>Ваше замовлення відправлено на ім’я: <strong><?= htmlspecialchars($user['name']) ?></strong></p>
    <p>Телефон: <strong><?= htmlspecialchars($user['phone']) ?></strong></p>
    <p>Адреса доставки: <strong><?= htmlspecialchars($user['address']) ?></strong></p>

    <div class="ordered-items">
        <div class="ordered-item header">
            <span>Назва товару</span>
            <span>Ціна за одиницю</span>
            <span>Кількість</span>
            <span>Сума</span>
        </div>

        <?php foreach ($orderedItems as $item): ?>
        <div class="ordered-item">
            <span><?= htmlspecialchars($item['productName']) ?></span>
            <span><?= number_format($item['unitPrice'], 2) ?> грн</span>
            <span><?= (int)$item['quantity'] ?></span>
            <span><?= number_format($item['unitPrice'] * $item['quantity'], 2) ?> грн</span>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="total-row">
        <span>Загальна сума:</span>
        <span><?= number_format($totalAmount, 2) ?> грн</span>
    </div>

    <a href="index.php?controller=products&action=list" class="btn-home">Повернутися на головну</a>
</div>
