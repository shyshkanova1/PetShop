<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<div style="max-width: 700px; margin: 40px auto; padding: 30px; background: #ffe0e0; border: 2px solid #f44336; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); font-family: Arial, sans-serif;">
    <h1 style="color: #f44336; margin-bottom: 20px; text-align: center;">Неможливо оформити замовлення</h1>
    <p style="color: #9c1c1c; font-size: 16px; text-align: center; margin-bottom: 30px;">
        У вас вже є незавершене замовлення. Будь ласка, завершить його перед тим, як оформлювати нове.
    </p>
    <div style="text-align: center;">
        <a href="index.php?controller=users&action=orders" style="display: inline-block; padding: 12px 25px; background: #38a169; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; transition: 0.2s;">
            Перейти до моїх замовлень
        </a>
    </div>
</div>
