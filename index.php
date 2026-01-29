<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ========================
// Конфігурації
// ========================
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/core/ORM_init.php';

// ========================
// Визначаємо, чи це AJAX-запит
// ========================
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// ========================
// При AJAX приховуємо вивід помилок
// ========================
if ($isAjax) {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// ========================
// Підключаємо navbar тільки для звичайних запитів
// ========================
if (!$isAjax) {
    require_once __DIR__ . '/views/navbar.php';
}

// ========================
// Роутинг
// ========================
$controller = $_GET['controller'] ?? 'products';
$action = $_GET['action'] ?? 'list';

$controllerFile = __DIR__ . "/controllers/" . ucfirst($controller) . "Controller.php";

if (!file_exists($controllerFile)) {
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'Контролер не знайдено']);
        exit;
    } else {
        die("Контролер не знайдено: $controllerFile");
    }
}

require_once $controllerFile;
$controllerClass = ucfirst($controller) . "Controller";
$obj = new $controllerClass($pdo);

if (!method_exists($obj, $action)) {
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => "Метод $action не знайдено"]);
        exit;
    } else {
        die("Метод $action не знайдено в $controllerClass");
    }
}

// ========================
// Виконуємо дію
// ========================
if ($isAjax) {
    header('Content-Type: application/json; charset=utf-8');
    // Викликаємо метод контролера
    $obj->$action();
    exit; // дуже важливо, щоб не виводився HTML
} else {
    echo '<div class="main-content">';
    $obj->$action();
    echo '</div>';

    require_once __DIR__ . '/views/footer.php';
}
