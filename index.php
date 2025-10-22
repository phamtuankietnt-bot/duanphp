<?php
// File chính - Router đơn giản
require_once 'config/database.php';
require_once 'controllers/HomeController.php';

// Lấy action từ URL
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Tạo controller
$controller = new HomeController();

// Routing đơn giản
switch($action) {
    case 'index':
        $controller->index();
        break;
    case 'products':
        $controller->products();
        break;
    case 'productDetail':
        $controller->productDetail();
        break;
    case 'about':
        $controller->about();
        break;
    case 'contact':
        $controller->contact();
        break;
    case 'cart':
        $controller->cart();
        break;
    case 'checkout':
        $controller->checkout();
        break;
    case 'orderSuccess':
        $controller->orderSuccess();
        break;
    case 'articles':
        $controller->articles();
        break;
    case 'articleDetail':
        $controller->articleDetail();
        break;
    case 'login':
        $controller->login();
        break;
    case 'register':
        $controller->register();
        break;
    case 'logout':
        $controller->logout();
        break;
    case 'profile':
        $controller->profile();
        break;
    case 'orders':
        $controller->orders();
        break;
    case 'orderDetail':
        $controller->orderDetail();
        break;
    case 'cancelOrder':
        $controller->cancelOrder();
        break;
    default:
        // 404 - trang không tồn tại
        include 'views/404.php';
        break;
}
?>
