<h3><?= htmlspecialchars($category['name']) ?></h3>
<p><?= htmlspecialchars($category['description']) ?></p>

<h4>Товари в цій категорії:</h4>

<?php if (!empty($products)): ?>
    <ul>
    <?php foreach ($products as $prod): ?>
        <li>
            <a href="index.php?controller=products&action=view&id=<?= $prod['productId'] ?>">
                <?= htmlspecialchars($prod['name']) ?> - <?= htmlspecialchars($prod['price']) ?> грн
            </a>
        </li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Товари відсутні у цій категорії.</p>
<?php endif; ?>
