<h3>Категорії</h3>
<ul>
    <?php foreach ($categories as $cat): ?>
        <li>
            <a href="index.php?controller=category&action=view&id=<?= $cat['categoryId'] ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
