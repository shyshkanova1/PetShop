<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
?>

<h2>Ваш кошик</h2>

<?php if (empty($cart)): ?>
    <div class="empty-cart">
        <p>Ваш кошик порожній</p>
        <a href="index.php?controller=products&action=list" class="btn btn-back">Повернутися до товарів</a>
    </div>
<?php else: ?>
    <div class="cart-container">
        <?php $total = 0; ?>
        <?php foreach ($cart as $item): ?>
            <?php $sum = $item['price'] * $item['quantity']; ?>
            <?php $total += $sum; ?>
            <div class="cart-item" data-product-id="<?= $item['productId'] ?>">
                <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
                <div class="item-price"><?= number_format($item['price'], 2) ?> ₴</div>
                <div class="item-quantity">
                    <input 
                        type="number"
                        class="qty-input"
                        data-product-id="<?= $item['productId'] ?>"
                        value="<?= $item['quantity'] ?>"
                        min="1"
                    >
                </div>
                <div class="item-sum"><?= number_format($sum, 2) ?> ₴</div>
                <div class="item-action">
                    <a href="index.php?controller=cart&action=remove&productId=<?= $item['productId'] ?>" class="btn btn-remove">Видалити</a>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="cart-total">
            <span>Разом:</span>
            <span class="total-value"><?= number_format($total, 2) ?> ₴</span>
        </div>

        <div class="cart-actions">
            <a href="index.php?controller=cart&action=checkout" class="btn btn-checkout">Оформити замовлення</a>
        </div>
    </div>
<?php endif; ?>

<style>
h2 { text-align: center; margin-bottom: 20px; color: #333; font-family: Arial, sans-serif; }

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

.cart-container { max-width: 900px; margin: 0 auto; }

.cart-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #fafafa;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 8px;
    box-shadow: 0 1px 5px rgba(0,0,0,0.1);
    font-family: Arial, sans-serif;
}

.cart-item:hover { background-color: #f0f8ff; }

.cart-item {
    display: flex;
    align-items: center; 
    justify-content: space-between;
    background-color: #fafafa;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 8px;
    box-shadow: 0 1px 5px rgba(0,0,0,0.1);
}

.item-action {
    display: flex;
    align-items: center;
    justify-content: center;
}


.item-name { flex: 2; text-align: left; }
.item-price, .item-sum, .item-quantity { flex: 1; }


.qty-input { width: 60px; padding: 5px; border-radius: 5px; border: 1px solid #ccc; text-align: center; }

.btn { display: inline-block; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; border: none; cursor: pointer; transition: background-color 0.3s; }
.btn:hover { opacity: 0.9; }
.btn-remove { background-color: #f44336; color: #fff; }

.cart-actions { text-align: center; margin-top: 20px; }
.btn-checkout {
    display: inline-block;
    background-color: #4caf50;
    color: #fff;
    font-size: 1.2em;
    padding: 12px 25px;
    border-radius: 8px;
    font-weight: bold;
    transition: background-color 0.3s;
    text-decoration: none;
}
.btn-checkout:hover { background-color: #43a047; }

.cart-total {
    display: flex;
    justify-content: flex-end;
    font-size: 1.2em;
    font-weight: bold;
    margin-top: 10px;
    padding: 10px;
}
.cart-total span { margin-left: 10px; }
</style>

<script>
document.querySelectorAll('.qty-input').forEach(input => {
    input.addEventListener('change', function () {
        const productId = this.dataset.productId;
        const quantity = this.value;

        fetch('index.php?controller=cart&action=update', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `productId=${productId}&quantity=${quantity}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const row = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
                row.querySelector('.item-sum').textContent = data.itemTotal + ' ₴';
                document.querySelector('.cart-total .total-value').textContent = data.total + ' ₴';
            } else {
                alert(data.message || 'Помилка оновлення');
            }
        });
    });
});
</script>
