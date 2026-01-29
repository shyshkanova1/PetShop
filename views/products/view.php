<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// $product, $reviews, $inWishlist, $userId приходять з контролера
?>

<style>
/* Основний блок продукту: збільшений, зображення зліва, інформація справа */
.product-container {
    display: flex;
    flex-wrap: wrap;
    max-width: 1000px; /* стало ширше */
    margin: 50px auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    padding: 35px; /* трохи більше відступу */
    font-family: Arial, sans-serif;
}

/* Зображення товару */
.product-image {
    flex: 1 1 400px;      /* трохи ширший блок */
    text-align: center;
    margin-right: 40px;
    height: 400px;         /* фіксована висота блоку */
    display: flex;
    justify-content: center;
    align-items: center;
}

.product-image img {
    max-width: 100%;
    max-height: 100%;      /* картинка заповнює блок */
    border-radius: 12px;
    object-fit: contain;   /* зберігаємо пропорції, нічого не обрізаємо */
}

/* Інформація про товар */
.product-info {
    flex: 2 1 500px; /* ширше і більший блок */
}

.product-info h2 {
    color: #2f855a;
    margin-top: 0;
    margin-bottom: 20px;
    font-size: 28px; /* збільшено шрифт назви */
}

.product-info p {
    margin: 10px 0;
    color: #4a5568;
    font-size: 17px; /* трохи більший шрифт для зручності */
    line-height: 1.5;
}

/* Кнопки користувача */
.buttons {
    margin-top: 25px;
}

.buttons a, .buttons button {
    display: inline-block;
    padding: 14px 24px; /* збільшено padding */
    margin-right: 12px;
    border-radius: 8px; /* трохи більший радіус */
    font-weight: bold;
    text-decoration: none;
    cursor: pointer;
    transition: 0.2s;
    border: none;
    font-size: 16px; /* більший шрифт */
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

/* Кнопки адміна */
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

/* Відгуки */
.reviews {
    margin-top: 40px;
    border-top: 1px solid #e2e8f0;
    padding-top: 25px;
}

.reviews h3 {
    color: #2f855a;
    font-size: 22px;
}

.review-item {
    margin-bottom: 18px;
    background: #f0fff4;
    padding: 12px 18px;
    border-radius: 8px;
    font-size: 16px;
}

.review-item strong {
    color: #22543d;
}

/* Додавання відгуку */
.add-review textarea {
    width: 100%;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #c6f6d5;
    margin-bottom: 12px;
    font-size: 15px;
}

.add-review select {
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #c6f6d5;
    margin-bottom: 12px;
    font-size: 15px;
}

.add-review button {
    background-color: #38a169;
    color: #fff;
    border: none;
    padding: 12px 22px;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    font-size: 16px;
}

.add-review button:hover {
    background-color: #2f855a;
}

/* Toast */
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
    <!-- Зображення товару -->
    <div class="product-image">
        <img src="<?= htmlspecialchars($product['imageUrl'] ?? 'uploads/products/default.jpg') ?>" 
     alt="<?= htmlspecialchars($product['name']) ?>">

    </div>

    <!-- Інформація про товар -->
    <div class="product-info">
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <p><strong>Ціна:</strong> <?= htmlspecialchars($product['price']) ?></p>
        <p><strong>Наявність:</strong> <?= htmlspecialchars($product['stock']) ?></p>
        <p><strong>Опис:</strong> <?= htmlspecialchars($product['description']) ?></p>
        <p><strong>Категорія:</strong> <?= htmlspecialchars($product['categoryName'] ?? 'Невідомо') ?></p>
        <p><strong>Середній рейтинг:</strong> <?= number_format($averageRating, 2) ?> / 5</p>


        <!-- Кнопки користувача -->
        <div class="buttons">
            <a href="index.php?controller=cart&action=add&productId=<?= $product['productId'] ?>" class="add-cart">Додати в кошик</a>

            <?php if ($userId): ?>
                <button id="addToWishlistBtn" data-product-id="<?= $product['productId'] ?>" class="wishlist">Додати у Wishlist</button>
            <?php endif; ?>
        </div>

        <!-- Кнопки адміна -->
        <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <div class="admin-buttons">
                <a href="index.php?controller=products&action=edit&id=<?= $product['productId'] ?>">Редагувати</a>
                <a href="index.php?controller=products&action=delete&id=<?= $product['productId'] ?>" onclick="return confirm('Ви впевнені?')">Видалити</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Відгуки -->
<!-- Відгуки -->
<div class="reviews">
    <h3>Відгуки</h3>
    <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $rev): ?>
            <div class="review-item">
                <strong><?= htmlspecialchars($rev->userName) ?></strong>
                (<?= $rev->rating ?>/5) — <?= htmlspecialchars($rev->comment) ?>

                <!-- Кнопка Видалити: тільки автор або адмін -->
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
/* Кнопка Видалити відгук */
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


<!-- Toast -->
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
