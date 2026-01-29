<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<h2>Додати новий товар</h2>

<form method="post" action="" enctype="multipart/form-data">
    <label>Назва:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Ціна:</label><br>
    <input type="number" step="0.01" name="price" required><br><br>

    <label>Наявність:</label><br>
    <input type="number" name="stock" required><br><br>

    <label>Опис:</label><br>
    <textarea name="description"></textarea><br><br>

    <label>Категорія (ID):</label><br>
    <input type="number" name="categoryID"><br><br>

    <label>Зображення товару:</label><br>
    <input type="file" name="image" accept="image/*"><br><br>

    <button type="submit">Додати товар</button>
</form>

<p><a href="index.php?controller=products&action=list">Назад до списку товарів</a></p>

<style>
/* ==========================
   ФОРМА ДОДАВАННЯ ТОВАРУ
========================== */
h2 {
    color: #4CAF50;
    font-family: 'Arial', sans-serif;
    margin-bottom: 20px;
    text-align: center; 
}

form {
    background-color: #f9f9f9;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    max-width: 500px;
    margin: 0 auto;
    font-family: 'Arial', sans-serif;
}

form label {
    font-weight: bold;
    display: block;
    margin-bottom: 6px;
    color: #333;
}

form input[type="text"],
form input[type="number"],
form input[type="file"],
form textarea {
    width: 100%;
    padding: 8px 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
    transition: 0.2s;
}

form input[type="text"]:focus,
form input[type="number"]:focus,
form textarea:focus {
    border-color: #4CAF50;
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
    outline: none;
}

form textarea {
    min-height: 100px;
    resize: vertical;
}

form button[type="submit"] {
    background-color: #4CAF50;
    color: #fff;
    padding: 10px 18px;
    border: none;
    border-radius: 6px;
    font-size: 15px;
    cursor: pointer;
    transition: 0.2s;
}

form button[type="submit"]:hover {
    background-color: #45a049;
}

p a {
    color: #4CAF50;
    text-decoration: none;
    font-weight: bold;
    transition: 0.2s;
}

p a:hover {
    text-decoration: underline;
}
</style>
