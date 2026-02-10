<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<div class="admin-add-product">

<h2>Додати новий товар</h2>

<form method="post" action="" enctype="multipart/form-data">
    <label>Назва:</label>
    <input type="text" name="name" required>

    <label>Ціна:</label>
    <input type="number" step="0.01" name="price" required>

    <label>Наявність:</label>
    <input type="number" name="stock" required>

    <label>Опис:</label>
    <textarea name="description"></textarea>

    <label for="categoryId">Категорія:</label>
    <select name="categoryId" id="categoryId" required>
        <option value="" disabled selected> Оберіть категорію</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['categoryId'] ?>">
                <?= htmlspecialchars($category['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Зображення товару:</label>
    <input type="file" name="image" accept="image/*">

    <button type="submit">Додати товар</button>
</form>

<p><a href="index.php?controller=products&action=list">Назад до списку товарів</a></p>

</div>


<style>
.admin-add-product h2 {
    color: #4CAF50;
    font-family: Arial, sans-serif;
    margin-bottom: 20px;
    text-align: center;
}

.admin-add-product form {
    background-color: #f9f9f9;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    max-width: 520px;
    margin: 0 auto;
    font-family: Arial, sans-serif;
}

.admin-add-product label {
    font-weight: bold;
    display: block;
    margin-bottom: 6px;
    color: #333;
}

.admin-add-product input[type="text"],
.admin-add-product input[type="number"],
.admin-add-product input[type="file"],
.admin-add-product textarea,
.admin-add-product select {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
    background-color: #fff;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.admin-add-product textarea {
    min-height: 100px;
    resize: vertical;
}

.admin-add-product input:focus,
.admin-add-product textarea:focus,
.admin-add-product select:focus {
    outline: none;
    border-color: #4CAF50;
    box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
}


.admin-add-product button {
    background-color: #4CAF50;
    color: #fff;
    padding: 10px 18px;
    border: none;
    border-radius: 6px;
    font-size: 15px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.admin-add-product button:hover {
    background-color: #45a049;
}

.admin-add-product p a {
    color: #4CAF50;
    text-decoration: none;
    font-weight: bold;
}

.admin-add-product p a:hover {
    text-decoration: underline;
}

</style>
