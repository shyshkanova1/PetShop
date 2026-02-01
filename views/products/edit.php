<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<style>
/* ===== Адмін-форма (товар) ===== */
.admin-form {
    max-width: 620px;
    margin: 30px auto;
    font-family: Arial, sans-serif;
    font-size: 14px;
    padding: 25px 30px;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.admin-form h2 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 22px;
    color: #333;
}

.admin-form div {
    margin-bottom: 16px;
}

.admin-form label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #444;
}

.admin-form input[type="text"],
.admin-form input[type="number"],
.admin-form input[type="file"],
.admin-form textarea,
.admin-form select {
    width: 100%;
    padding: 10px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    box-sizing: border-box;
    background-color: #fff;
    font-size: 14px;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.admin-form select:focus {
    outline: none;
    border-color: #4CAF50;
    box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
}


.admin-form textarea {
    min-height: 100px;
    resize: vertical;
}

.admin-form input:focus,
.admin-form textarea:focus {
    outline: none;
    border-color: #4CAF50;
    box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
}

.admin-form button {
    background-color: #4CAF50;
    color: #fff;
    padding: 10px 18px;
    border: none;
    border-radius: 6px;
    font-size: 15px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.admin-form button:hover {
    background-color: #45a049;
}

.admin-form a {
    display: inline-block;
    margin-top: 15px;
    color: #4CAF50;
    font-weight: bold;
    text-decoration: none;
    transition: color 0.2s;
}

.admin-form a:hover {
    text-decoration: underline;
}
</style>

<form class="admin-form" method="post" action="" enctype="multipart/form-data">
    <h2>Редагувати товар</h2>

    <div>
        <label>Назва:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product->name) ?>" required>
    </div>

    <div>
        <label>Ціна:</label>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product->price) ?>" required>
    </div>

    <div>
        <label>Наявність:</label>
        <input type="number" name="stock" value="<?= htmlspecialchars($product->stock) ?>" required>
    </div>

    <div>
        <label>Опис:</label>
        <textarea name="description"><?= htmlspecialchars($product->description) ?></textarea>
    </div>

    <div>
            <label for="categoryId">Категорія</label>
    <select name="categoryId" id="categoryId" required>
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['categoryId'] ?>"
                <?= $category['categoryId'] == $product->categoryID ? 'selected' : '' ?>>
                <?= htmlspecialchars($category['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    </div>

    <div>
        <label>Зображення товару (залиште порожнім, якщо не змінювати):</label>
        <input type="file" name="image" accept="image/*">
        <?php if (!empty($product->imageUrl)): ?>
            <p>Поточне зображення:</p>
            <img src="<?= htmlspecialchars($product->imageUrl) ?>" alt="<?= htmlspecialchars($product->name)?>" style="max-width:150px;">
        <?php endif; ?>
    </div>

    <button type="submit">Оновити товар</button>

    <p><a href="index.php?controller=products&action=list">Назад до списку товарів</a></p>
</form>
