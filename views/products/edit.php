<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<h2>Редагувати товар</h2>

<form method="post" action="" enctype="multipart/form-data">
    <label>Назва:</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required><br><br>

    <label>Ціна:</label><br>
    <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price']) ?>" required><br><br>

    <label>Наявність:</label><br>
    <input type="number" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required><br><br>

    <label>Опис:</label><br>
    <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea><br><br>

    <label>Категорія (ID):</label><br>
    <input type="number" name="categoryID" value="<?= htmlspecialchars($product['categoryId']) ?>"><br><br>

    <label>Зображення товару (залиште порожнім, якщо не змінювати):</label><br>
    <input type="file" name="image" accept="image/*"><br>
    <?php if (!empty($product['imageUrl'])): ?>
        <p>Поточне зображення:</p>
        <img src="<?= htmlspecialchars($product['imageUrl']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="max-width:150px;">
    <?php endif; ?>
    <br><br>

    <button type="submit">Оновити товар</button>
</form>

<p><a href="index.php?controller=products&action=list">Назад до списку товарів</a></p>
