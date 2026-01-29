<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<div style="max-width: 700px; margin: 40px auto; padding: 30px; background: #fff3cd; border: 2px solid #ffc107; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); font-family: Arial, sans-serif;">
    <h1 style="color: #856404; margin-bottom: 20px; text-align: center;">Неможливо оформити замовлення</h1>
    <p style="color: #5f4b00; font-size: 16px; text-align: center; margin-bottom: 30px;">
        На жаль, на складі недостатньо товару для оформлення замовлення.
    </p>
    <div style="text-align: center;">
        <a href="index.php?controller=cart&action=view" style="display: inline-block; padding: 12px 25px; background: #38a169; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; transition: 0.2s;">
            Повернутися до кошика
        </a>
    </div>
</div>
