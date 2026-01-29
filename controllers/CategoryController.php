<?php
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Product.php';

class CategoryController {
    private $categoryModel;
    private $productModel;

    public function __construct($pdo) {
        $this->categoryModel = new Category($pdo);
        $this->productModel = new Product($pdo);
    }

    // Відображення списку категорій
    public function list() {
        $categories = $this->categoryModel->getAll();
        require_once __DIR__ . '/../views/categories/list.php';
    }

    // Відображення конкретної категорії з описом та товарами
    public function view() {
        $categoryId = $_GET['id'] ?? null;
        if (!$categoryId) {
            header('Location: index.php?controller=category&action=list');
            exit;
        }

        $category = $this->categoryModel->getById($categoryId);
        $products = $this->productModel->getByCategory($categoryId);

        require_once __DIR__ . '/../views/categories/view.php';
    }
    
}
