<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/core/ORM_init.php';

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($isAjax) {
    ini_set('display_errors', 0);
    error_reporting(0);
}

if (!$isAjax) {
    require_once __DIR__ . '/views/navbar.php';
}

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

if ($isAjax) {
    header('Content-Type: application/json; charset=utf-8');
    
    $obj->$action();
    exit; 
} else {
    echo '<div class="main-content">';
    $obj->$action();
    echo '</div>';

    require_once __DIR__ . '/views/footer.php';
}
