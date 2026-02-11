<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<style>
    .orders-container {
        max-width: 800px;
        margin: 40px auto;
        padding: 30px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        font-family: Arial, sans-serif;
    }

    .orders-container h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #2f855a; /* зелений заголовок */
    }

    .order-card {
        border: 1px solid #d1e7dd;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
        background: #f0fff4;
    }

    .order-header {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #276749; /* темний зелений */
    }

    .order-header span {
        font-weight: normal;
        color: #22543d;
    }

    .shipping-address {
        margin-bottom: 15px;
        color: #2d3748;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }

    .order-table th,
    .order-table td {
        border: 1px solid #c6f6d5;
        padding: 8px;
        text-align: left;
    }

    .order-table th {
        background-color: #9ae6b4;
        color: #22543d;
    }

    .order-table td {
        background-color: #d9f7d6;
        color: #1a202c;
    }

    .btn {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        cursor: pointer;
        border: none;
        transition: 0.2s;
    }

    .btn-cancel {
        background-color: #e53e3e; /* червоне для скасування */
        color: #fff;
    }

    .btn-cancel:hover {
        background-color: #c53030;
    }

    .btn-profile {
        background-color: #38a169; /* зелена кнопка для повернення */
        color: #fff;
    }

    .btn-profile:hover {
        background-color: #2f855a;
    }

    em {
        color: #718096;
    }

</style>

<div class="orders-container">
    <h2>Історія замовлень</h2>

    <?php if (empty($orders)): ?>
        <p>У вас ще немає замовлень.</p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="order-card">
                <div class="order-header">
                    Замовлення #<?= $order['orderId'] ?> | 
                    <span>Статус: <?= htmlspecialchars($order['status']) ?></span> | 
                    <span>Сума: <?= $order['totalAmount'] ?></span>
                </div>

                <div class="shipping-address">
                    Адреса доставки: <?= htmlspecialchars($order['shippingAddress']) ?>
                </div>

                <table class="order-table">
                    <tr>
                        <th>Товар</th>
                        <th>Ціна за одиницю</th>
                        <th>Кількість</th>
                    </tr>
                    <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['productName']) ?></td>
                            <td><?= $item['unitPrice'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>

                <?php if ($order['status'] !== 'cancelled'): ?>
                    <form action="index.php?controller=orders&action=cancel" method="post" style="margin-top:10px;">
                        <input type="hidden" name="orderId" value="<?= $order['orderId'] ?>">
                        <button type="submit" class="btn btn-cancel">
                            Скасувати замовлення
                        </button>
                    </form>
                <?php else: ?>
                    <p><em>Замовлення скасоване</em></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="index.php?controller=users&action=profile" class="btn btn-profile">Повернутися до профілю</a>
</div>
