<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<link rel="stylesheet" href="assets/css/style.css">

<?php if (!empty($categoryDescription)): ?>
    <p><em><?= htmlspecialchars($categoryDescription) ?></em></p>
<?php endif; ?>

<div class="products-container">

    <?php if (!empty($products) && is_array($products)): ?>
        <?php foreach ($products as $product): ?>

            <?php
                $imageUrl = !empty($product['imageUrl'])
                    ? htmlspecialchars($product['imageUrl'])
                    : 'uploads/products/default.jpg';
                    
            ?>
            <div class="product-card">
                <a href="index.php?controller=products&action=view&id=<?= $product['productId'] ?>">
                    <img src="<?= $imageUrl ?>"
                         alt="<?= htmlspecialchars($product['name']) ?>">

                    <h4><?= htmlspecialchars($product['name']) ?></h4>

                    <p class="price">
                        <?= htmlspecialchars($product['price']) ?> ₴
                    </p>

                    <p class="stock">
                        <?= $product['stock'] > 0
                            ? 'В наявності'
                            : 'Немає в наявності'
                        ?>
                    </p>
                </a>

                <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <div class="actions">
                        <a href="index.php?controller=products&action=edit&id=<?= $product['productId'] ?>">
                            Редагувати
                        </a>
                        <a href="index.php?controller=products&action=delete&id=<?= $product['productId'] ?>"
                           onclick="return confirm('Ви впевнені?')">
                            Видалити
                        </a>
                    </div>
                <?php endif; ?>
            </div>

        <?php endforeach; ?>
    <?php else: ?>
        <p>Товари не знайдені</p>
    <?php endif; ?>

    <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <div class="product-card add-product-card">
            <a href="index.php?controller=products&action=add">
                <div class="add-product-content">
                    <h4>+ Додати товар</h4>
                </div>
            </a>
        </div>
    <?php endif; ?>

</div>
