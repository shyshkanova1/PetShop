<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/Category.php';
$categoryModel = new Category($pdo);
$categories = $categoryModel->getAll();
?>
<link rel="stylesheet" href="assets/css/style.css">
<nav class="navbar">
    <div class="navbar-left">
        <a href="index.php" class="logo"> MyPetShop</a>
        
        <form action="index.php?controller=products&action=list" method="get" class="search-form">
            <div class="search-wrapper">
                <input type="text" name="name" placeholder="Пошук товарів..." 
                       value="<?= isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '' ?>" required>
                <button type="submit">Пошук</button>
            </div>
        </form>

        <div class="contact-info">
            <span> +380 99 123 45 67</span> | 
            <span>Пн-Пт 9:00-18:00</span>
        </div>
    </div>
    <div class="navbar-right">
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="index.php?controller=users&action=profile" class="nav-btn">Особистий кабінет</a>
            
            <?php if($_SESSION['role'] === 'admin'): ?>
                <a href="index.php?controller=adminUsers&action=list" class="nav-btn">Список користувачів</a>
                <a href="index.php?controller=adminOrders&action=list" class="nav-btn">Список замовлень</a>
            <?php else: ?>
                <a href="index.php?controller=wishlist&action=view" class="nav-btn">Wishlist</a>
                <a href="index.php?controller=cart&action=view" class="nav-btn">Кошик</a>
            <?php endif; ?>

            <a href="index.php?controller=users&action=logout" class="nav-btn logout">Вийти</a>
        <?php else: ?>
            <a href="index.php?controller=users&action=login" class="nav-btn">Увійти</a>
            <a href="index.php?controller=users&action=signup" class="nav-btn">Зареєструватися</a>
        <?php endif; ?>
    </div>
</nav>

<div class="categories-bar">
    <a href="index.php?controller=products&action=list" class="category-button">Всі</a>
    <?php foreach ($categories as $cat): ?>
        <a href="index.php?controller=products&action=list&categoryId=<?= $cat['categoryId'] ?>" 
           class="category-button">
            <?= htmlspecialchars($cat['name']) ?>
        </a>
    <?php endforeach; ?>
</div>