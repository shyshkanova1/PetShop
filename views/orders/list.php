<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f7f7f7;
        color: #333;
        margin: 20px;
    }

    h1 {
        text-align: center;
        color: #4CAF50;
        margin-bottom: 20px;
    }

    p.message {
        text-align: center;
        font-weight: bold;
        padding: 10px;
        border-radius: 5px;
        width: fit-content;
        margin: 10px auto;
    }

    p.success {
        color: #155724;
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
    }

    p.error {
        color: #721c24;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
    }

    .recalc-btn, .action-btn {
        background-color: #4CAF50; 
        color: white;
        border: none;
        padding: 8px 16px;
        font-weight: bold;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.2s;
        font-size: 16px;
    }

    .recalc-btn:hover, .action-btn:hover {
        background-color: #1e8449;
    }

    form.filter-form {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 20px;
        align-items: center;
    }

    form.filter-form label {
        font-weight: bold;
    }

    form.filter-form select {
        padding: 5px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    .order-table {
        display: flex;
        flex-direction: column;
        gap: 5px;
        max-width: 1500px; 
        margin: 0 auto;
    }

    .order-header, .order-row {
        display: flex;
        padding: 10px;
        border-radius: 5px;
    }

    .order-header {
        background-color: #4CAF50;
        color: white;
        font-weight: bold;
    }

    .order-row {
        background-color: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        align-items: center;
        flex-wrap: wrap;
    }

    .order-row:nth-child(even) {
        background-color: #e8f5e9; 
    }

    .order-cell {
        flex: 1;
        padding: 5px 10px;
        min-width: 100px;
        text-align: center;
    }

    a.delete-link {
        display: inline-block;
        font-size: 16px;
        padding: 8px 16px;
        background-color: #ef4444; 
        color: white;
        border-radius: 5px;
        font-weight: bold;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    a.delete-link:hover {
        color: #922b21;
    }

    .status-form {
        display: flex;
        justify-content: center; 
        gap: 8px;
        flex-wrap: wrap;
    }


    .status-form select {
        padding: 4px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

.order-cell > div {
    margin-bottom: 5px; 
}

</style>

<h1>Список замовлень</h1>

<?php if (!empty($_SESSION['success_message'])): ?>
    <p class="message success"><?= $_SESSION['success_message'] ?></p>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error_message'])): ?>
    <p class="message error"><?= $_SESSION['error_message'] ?></p>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<form method="post" action="index.php?controller=adminOrders&action=recalcTotals" style="text-align:center; margin-bottom:20px;">
    <button type="submit" class="recalc-btn">Перерахувати суми замовлень</button>
</form>

<form method="get" action="index.php" class="filter-form">
    <input type="hidden" name="controller" value="adminOrders">
    <input type="hidden" name="action" value="list">

    <label>Статус:
        <select name="status">
            <option value="">Всі</option>
            <?php foreach ($statuses as $s): ?>
                <option value="<?= htmlspecialchars($s) ?>" <?= ($s === ($_GET['status'] ?? '')) ? 'selected' : '' ?>>
                    <?= htmlspecialchars(ucfirst($s)) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Користувач:
        <select name="userId">
            <option value="">Всі</option>
            <?php foreach ($users as $u): ?>
                <option value="<?= $u['userId'] ?>" <?= ($_GET['userId'] ?? '') == $u['userId'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <button type="submit" class="action-btn">Фільтрувати</button>
</form>

<div class="order-table">
    <div class="order-header">
        <div class="order-cell">Користувач</div>
        <div class="order-cell">Замовлені товари</div>
        <div class="order-cell">Сума</div>
        <div class="order-cell">Статус</div>
        <div class="order-cell">Дата</div>
        <div class="order-cell">Адреса доставки</div>
        <div class="order-cell">Дії</div>
    </div>

    <?php foreach ($orders as $o): ?>
        <div class="order-row">
            <div class="order-cell"><?= htmlspecialchars($o['userName']) ?></div>

            <div class="order-cell">
                <?php foreach ($o['items'] as $item): ?>
                    <div><?= htmlspecialchars($item['productName']) ?> x<?= $item['quantity'] ?></div>
                <?php endforeach; ?>
            </div>

            <div class="order-cell"><?= $o['totalAmount'] ?></div>

            <div class="order-cell">
                <form method="post" action="index.php?controller=adminOrders&action=updateStatus" class="status-form">
                    <input type="hidden" name="orderId" value="<?= $o['orderId'] ?>">
                    <select name="status">
                        <?php foreach ($statuses as $s): ?>
                            <option value="<?= htmlspecialchars($s) ?>" <?= $o['status'] === $s ? 'selected' : '' ?>>
                                <?= htmlspecialchars(ucfirst($s)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="action-btn">Змінити</button>
                </form>
            </div>

            <div class="order-cell"><?= $o['createdAt'] ?></div>
            <div class="order-cell"><?= htmlspecialchars($o['shippingAddress']) ?></div>
            <div class="order-cell">
                <a href="index.php?controller=adminOrders&action=delete&id=<?= $o['orderId'] ?>"
                   class="delete-link"
                   onclick="return confirm('Ви впевнені, що хочете видалити це замовлення?')">
                    Видалити
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>