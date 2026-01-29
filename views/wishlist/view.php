<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<h3>Мій Wishlist</h3>

<?php if (!empty($wishlistItems)): ?>
    <div class="wishlist-grid">
        <?php foreach ($wishlistItems as $item): ?>

            <?php
                $imageUrl = !empty($item->imageUrl)
                    ? htmlspecialchars($item->imageUrl)
                    : 'uploads/products/default.jpg';
            ?>

            <div class="productcard">
                <a href="index.php?controller=products&action=view&id=<?= $item->productId ?>">
                    <img src="<?= $imageUrl ?>"
                         alt="<?= htmlspecialchars($item->name) ?>"
                         class="product-image">

                    <h4 class="product-name">
                        <?= htmlspecialchars($item->name) ?>
                    </h4>
                </a>

                <p class="product-price">
                    Ціна: <?= htmlspecialchars($item->price) ?> ₴
                </p>

                <a href="index.php?controller=wishlist&action=remove&productId=<?= $item->productId ?>"
                   class="btn btn-remove">
                    Видалити
                </a>
            </div>

        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>Ваш Wishlist порожній.</p>
<?php endif; ?>

<div class="back-to-products">
    <a href="index.php?controller=products&action=list" class="btn btn-back">
        Повернутися до товарів
    </a>
</div>
