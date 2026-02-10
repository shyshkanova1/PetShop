<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<style>

.empty-cart {
    max-width: 500px;
    margin: 40px auto;
    text-align: center;
    background: #f0fff4;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.empty-cart p {
    font-size: 18px;
    margin-bottom: 20px;
    color: #2d3748;
}

.empty-cart .btn-back {
    display: inline-block;
    background-color: #4CAF50;
    color: #fff;
    font-size: 16px;
    padding: 12px 25px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s;
}

.empty-cart .btn-back:hover {
    background-color: #43a047;
}

.checkout-container {
    max-width: 600px;
    margin: 40px auto;
    padding: 30px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    font-family: Arial, sans-serif;
}

.checkout-container h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #43a047;
}

.checkout-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #2d3748;
}

.checkout-form input {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 20px;
    border-radius: 6px;
    border: 1px solid #d1e7dd;
    font-size: 14px;
    color: #1a202c;
    box-sizing: border-box;
}

.button-group {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
}

.button-group .btn-submit {
    padding: 12px 25px;
    border-radius: 6px;
    background-color: #4CAF50; 
    color: #fff;
    border: none;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.button-group .btn-submit:hover {
    background-color: #43a047;
}

.button-group .btn-back {
    padding: 12px 25px;
    border-radius: 6px;
    background-color: #ffffff;
    border: 2px solid #4CAF50;
    color: #4CAF50;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: 0.2s;
}

.button-group .btn-back:hover {
    background-color: #e6f4ea;
}
</style>

<div class="checkout-container">
    <h2>Оформлення замовлення</h2>

    <form class="checkout-form" action="index.php?controller=cart&action=checkout" method="POST">
        <label>Ім'я:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

        <label>Телефон:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>

        <label>Адреса доставки:</label>
        <input type="text" name="shipping_address" value="<?= htmlspecialchars($user['address']) ?>" required>

        <div class="button-group">
            <button type="submit" class="btn-submit">Оформити замовлення</button>
            <a href="index.php?controller=cart&action=view" class="btn-back">Повернутися до кошика</a>
        </div>
    </form>
</div>
