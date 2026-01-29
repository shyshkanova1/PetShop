<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Review.php';
require_once __DIR__ . '/../models/Category.php';

class ProductsController {
    private $pdo;
    private $productModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->productModel = new Product($pdo);
    }

    /* =======================
       Список товарів
    ======================== */
    public function list() {
        $filterName = $_GET['name'] ?? '';
        $categoryId = isset($_GET['categoryId']) ? intval($_GET['categoryId']) : null;

        $selectedCategory = null;
        $categoryDescription = '';

        if ($categoryId) {
            $categoryModel = new Category($this->pdo);
            $selectedCategory = $categoryModel->getById($categoryId);
            $categoryDescription = $selectedCategory['description'] ?? '';
        }

        $products = $this->productModel->getAll($filterName, $categoryId);

        $categoryModel = new Category($this->pdo);
        $categories = $categoryModel->getAll();

        require __DIR__ . '/../views/products/list.php';
    }

    /* =======================
       Додавання товару
    ======================== */
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $stock = $_POST['stock'] ?? 0;
            $description = $_POST['description'] ?? '';
            $categoryID = $_POST['categoryID'] ?? null;

            $imageUrl = 'uploads/products/default.jpg';

            if (!empty($_FILES['image']['name'])) {
                $uploadDir = 'uploads/products/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                $fileName = time().'_'.basename($_FILES['image']['name']);
                $targetFile = $uploadDir.$fileName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $imageUrl = $targetFile;
                }
            }

            $product = new Product($this->pdo);
            $product->name = $name;
            $product->price = $price;
            $product->stock = $stock;
            $product->description = $description;
            $product->categoryID = $categoryID;
            $product->imageUrl = $imageUrl;
            $product->save();

            header('Location: index.php?controller=products&action=list');
            exit;
        }

        require __DIR__ . '/../views/products/add.php';
    }

    /* =======================
       Редагування товару
    ======================== */
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) die('Не вказано ID товару для редагування');

        $productData = $this->productModel->getById($id);
        if (!$productData) die('Товар не знайдено');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $stock = $_POST['stock'] ?? 0;
            $description = $_POST['description'] ?? '';
            $categoryID = $_POST['categoryID'] ?? null;
            $imageUrl = $productData['imageUrl'];

            if (!empty($_FILES['image']['name'])) {
                $uploadDir = 'uploads/products/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                $fileName = time().'_'.basename($_FILES['image']['name']);
                $targetFile = $uploadDir.$fileName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $imageUrl = $targetFile;
                }
            }

            $product = new Product($this->pdo);
            $product->productId = $id;
            $product->name = $name;
            $product->price = $price;
            $product->stock = $stock;
            $product->description = $description;
            $product->categoryID = $categoryID;
            $product->imageUrl = $imageUrl;
            $product->save();

            header('Location: index.php?controller=products&action=list');
            exit;
        }

        require __DIR__ . '/../views/products/edit.php';
    }

    /* =======================
       Видалення товару
    ======================== */
    public function delete() {
        $id = $_GET['id'] ?? null;
        if (!$id) die('Не вказано ID товару');

        $product = new Product($this->pdo);
        $product->productId = $id;
        $product->isDeleted = 1;
        $product->save();

        header('Location: index.php?controller=products&action=list');
        exit;
    }

    /* =======================
       Перегляд товару
    ======================== */
    public function view() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $productId = $_GET['id'] ?? null;
        if (!$productId) {
            header('Location: index.php?controller=products&action=list');
            exit;
        }

        $product = $this->productModel->getById($productId);
        $averageRating = $this->productModel->getAverageRating($productId);

        $userId = $_SESSION['user_id'] ?? null;
        $inWishlist = false;
        if ($userId) {
            $inWishlist = $this->productModel->isInUserWishlist($productId, $userId);
        }

        $reviews = Review::getReviewsByProduct($this->pdo,$productId);

        require __DIR__ . '/../views/products/view.php';
    }
}
