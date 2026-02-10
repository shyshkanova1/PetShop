<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}?>

<style>
.product-container {
    display: flex;
    flex-wrap: wrap;
    max-width: 1000px; 
    margin: 50px auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    padding: 35px; 
    font-family: Arial, sans-serif;
}

.product-image {
    flex: 1 1 400px;      
    text-align: center;
    margin-right: 40px;
    height: 400px;         
    display: flex;
    justify-content: center;
    align-items: center;
}

.product-image img {
    max-width: 100%;
    max-height: 100%;      
    border-radius: 12px;
    object-fit: contain;  
}

.product-info {
    flex: 2 1 500px; 
}

.product-info h2 {
    color: #2f855a;
    margin-top: 0;
    margin-bottom: 20px;
    font-size: 28px; 
}

.product-info p {
    margin: 10px 0;
    color: #4a5568;
    font-size: 17px; 
    line-height: 1.5;
}

.buttons {
    margin-top: 25px;
}

.buttons a, .buttons button {
    display: inline-block;
    padding: 14px 24px;
    margin-right: 12px;
    border-radius: 8px; 
    font-weight: bold;
    text-decoration: none;
    cursor: pointer;
    transition: 0.2s;
    border: none;
    font-size: 16px; 
}

.buttons a.add-cart {
    background-color: #38a169;
    color: #fff;
}

.buttons a.add-cart:hover {
    background-color: #2f855a;
}

.buttons button.wishlist {
    background-color: #68d391;
    color: #fff;
}

.buttons button.wishlist:hover {
    background-color: #38a169;
}

.admin-buttons {
    margin-top: 20px;
    text-align: right;
}

.admin-buttons a {
    margin-left: 10px;
    color: #fff;
    background-color: #f56565;
    padding: 10px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
}

.admin-buttons a:hover {
    background-color: #c53030;
}

.reviews-wrapper {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 20px;
}

.reviews {
    background: #f9f9f9;
    border-radius: 14px;
    padding: 30px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.07);
}

.reviews h3 {
    text-align: center;
    font-size: 26px;
    color: #2f855a;
    margin-bottom: 30px;
}

.review-item {
    background: #ffffff;
    border-radius: 10px;
    padding: 16px 20px;
    margin-bottom: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    font-size: 16px;
    line-height: 1.5;
}

.review-item strong {
    color: #22543d;
}

.add-review {
    margin-top: 35px;
    background: #ffffff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 6px 16px rgba(0,0,0,0.06);
}

.add-review h4 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 22px;
    color: #2f855a;
}

.add-review form {
    max-width: 500px;
    margin: 0 auto;
}

.add-review textarea,
.add-review select {
    width: 100%;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #cbd5e0;
    font-size: 15px;
    margin-bottom: 15px;
}

.add-review button {
    display: block;
    margin: 0 auto;
    background-color: #38a169;
    color: #fff;
    border: none;
    padding: 12px 26px;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    font-size: 16px;
}

.add-review button:hover {
    background-color: #2f855a;
}

.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #333;
    color: #fff;
    padding: 14px 24px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    opacity: 0;
    transform: translateY(-20px);
    transition: opacity 0.4s, transform 0.4s;
    z-index: 1000;
    font-size: 15px;
}
.toast.show {
    opacity: 1;
    transform: translateY(0);
}
.toast.success { background-color: #4caf50; }
.toast.error { background-color: #f44336; }
</style>

<div class="product-container">
    <div class="product-image">
        <img src="<?= htmlspecialchars($product['imageUrl'] ?? 'uploads/products/default.jpg') ?>" 
     alt="<?= htmlspecialchars($product['name']) ?>">

    </div>

    <div class="product-info">
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <p><strong>Ціна:</strong> <?= htmlspecialchars($product['price']) ?></p>
        <p><strong>Наявність:</strong> <?= htmlspecialchars($product['stock']) ?></p>
        <p><strong>Опис:</strong> <?= htmlspecialchars($product['description']) ?></p>
        <p><strong>Категорія:</strong> <?= htmlspecialchars($product['categoryName'] ?? 'Невідомо') ?></p>
        <p><strong>Середній рейтинг:</strong> <?= number_format($averageRating, 2) ?> / 5</p>

        <div class="buttons">
            <a href="index.php?controller=cart&action=add&productId=<?= $product['productId'] ?>" class="add-cart">Додати в кошик</a>

            <?php if ($userId): ?>
                <button id="addToWishlistBtn" data-product-id="<?= $product['productId'] ?>" class="wishlist">Додати у Wishlist</button>
            <?php endif; ?>
        </div>

        <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <div class="admin-buttons">
                <a href="index.php?controller=products&action=edit&id=<?= $product['productId'] ?>">Редагувати</a>
                <a href="index.php?controller=products&action=delete&id=<?= $product['productId'] ?>" onclick="return confirm('Ви впевнені?')">Видалити</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="reviews">
    <h3>Відгуки</h3>
    <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $rev): ?>
            <div class="review-item">
                <strong><?= htmlspecialchars($rev->userName) ?></strong>
                (<?= $rev->rating ?>/5) — <?= htmlspecialchars($rev->comment) ?>

                <?php if ($userId && ($userId == $rev->userId || (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'))): ?>
                    <a href="index.php?controller=review&action=remove&reviewId=<?= $rev->reviewId ?>&productId=<?= $product['productId'] ?>" 
                       class="delete-review-btn" onclick>
                       Видалити
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Відгуків поки що немає.</p>
    <?php endif; ?>

    <?php if ($userId): ?>
        <div class="add-review">
            <h4>Додати відгук</h4>
            <form action="index.php?controller=review&action=add" method="post">
                <input type="hidden" name="productId" value="<?= $product['productId'] ?>">

                <label>Оцінка:
                    <select name="rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </label><br>
                <label>Коментар:<br>
                    <textarea name="comment" required></textarea>
                </label><br>
                <button type="submit">Додати відгук</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<style>
.delete-review-btn {
    display: inline-block;
    margin-left: 12px;
    padding: 4px 10px;
    background-color: #f56565;
    color: white;
    border-radius: 4px;
    font-size: 13px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.2s;
}

.delete-review-btn:hover {
    background-color: #c53030;
}
</style>
<div id="toast" class="toast"></div>

<script>
function showToast(message, success) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = 'toast show ' + (success ? 'success' : 'error');
    setTimeout(() => { toast.className = 'toast'; }, 3000);
}

document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('addToWishlistBtn');
    if (!btn) return;

    btn.addEventListener('click', () => {
        const productId = btn.dataset.productId;

        fetch('index.php?controller=wishlist&action=addAjax', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'productId=' + productId
        })
        .then(response => response.json())
        .then(data => showToast(data.message, data.success))
        .catch(err => {
            console.error(err);
            showToast('Помилка зʼєднання', false);
        });
    });
});
</script>
