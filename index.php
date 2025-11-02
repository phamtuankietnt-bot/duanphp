<?php
// File chính - Router đơn giản
require_once 'config/database.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/AdminController.php';

// Lấy action từ URL
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Kiểm tra nếu đã đăng nhập là admin và đang ở trang user, redirect đến admin
require_once 'config/session.php';
startSession();
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin' && $action != 'admin' && $action != 'logout') {
    // Nếu admin đang truy cập trang user, redirect đến admin panel
    if (!in_array($action, ['admin', 'logout', 'login', 'register'])) {
        header('Location: index.php?action=admin&page=dashboard');
        exit();
    }
}

// Routing cho admin
if ($action == 'admin') {
    $admin_controller = new AdminController();
    $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
    
    switch($page) {
        case 'dashboard':
            $admin_controller->dashboard();
            break;
        case 'users':
            $admin_controller->users();
            break;
        case 'products':
            $admin_controller->products();
            break;
        case 'articles':
            $admin_controller->articles();
            break;
        case 'orders':
            $admin_controller->orders();
            break;
        default:
            $admin_controller->dashboard();
            break;
    }
    exit();
}

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
