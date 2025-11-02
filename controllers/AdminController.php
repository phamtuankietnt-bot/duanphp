<?php
require_once 'models/Product.php';
require_once 'models/User.php';
require_once 'models/Order.php';
require_once 'models/Article.php';
require_once 'models/Cart.php';
require_once 'config/database.php';
require_once 'config/session.php';
require_once 'config/gemini.php';

class AdminController {
    private $product;
    private $user;
    private $order;
    private $article;
    private $cart;
    private $db;

    public function __construct() {
        startSession();
        $this->checkAdmin();
        
        $database = new Database();
        $this->db = $database->getConnection();
        $this->product = new Product($this->db);
        $this->user = new User($this->db);
        $this->order = new Order($this->db);
        $this->article = new Article($this->db);
        $this->cart = new Cart($this->db);
    }

    // Kiểm tra quyền admin
    private function checkAdmin() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
            header('Location: index.php?action=login');
            exit();
        }
    }

    // Dashboard
    public function dashboard() {
        $page_title = 'Dashboard';
        // Thống kê tổng quan
        $stats = [
            'total_products' => $this->getTotalProducts(),
            'total_users' => $this->getTotalUsers(),
            'total_orders' => $this->getTotalOrders(),
            'total_revenue' => $this->getTotalRevenue(),
            'pending_orders' => $this->getPendingOrders(),
            'total_articles' => $this->getTotalArticles()
        ];

        // Đơn hàng mới nhất
        $recent_orders = $this->order->getAllOrders();
        $recent_orders = array_slice($recent_orders, 0, 10);

        // Sản phẩm bán chạy (top 5)
        $top_products = $this->getTopProducts();
        
        // Doanh thu hàng tháng (12 tháng gần nhất)
        $monthly_revenue = $this->getMonthlyRevenue();

        include 'views/admin/layout/header.php';
        include 'views/admin/dashboard.php';
        include 'views/admin/layout/footer.php';
    }

    // Quản lý người dùng
    public function users() {
        $sub_action = isset($_GET['sub']) ? $_GET['sub'] : 'list';
        $page_title = 'Quản lý người dùng';
        
        switch($sub_action) {
            case 'list':
                $users = $this->getAllUsers();
                include 'views/admin/layout/header.php';
                include 'views/admin/users/list.php';
                include 'views/admin/layout/footer.php';
                break;
            case 'add':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $this->addUser();
                }
                include 'views/admin/layout/header.php';
                include 'views/admin/users/add.php';
                include 'views/admin/layout/footer.php';
                break;
            case 'edit':
                $id = isset($_GET['id']) ? $_GET['id'] : 0;
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $this->updateUser($id);
                }
                $user = $this->getUserById($id);
                include 'views/admin/layout/header.php';
                include 'views/admin/users/edit.php';
                include 'views/admin/layout/footer.php';
                break;
            case 'delete':
                $id = isset($_GET['id']) ? $_GET['id'] : 0;
                $this->deleteUser($id);
                break;
            default:
                $users = $this->getAllUsers();
                include 'views/admin/layout/header.php';
                include 'views/admin/users/list.php';
                include 'views/admin/layout/footer.php';
                break;
        }
    }

    // Quản lý sản phẩm
    public function products() {
        $sub_action = isset($_GET['sub']) ? $_GET['sub'] : 'list';
        $page_title = 'Quản lý sản phẩm';
        
        switch($sub_action) {
            case 'list':
                $products = $this->getAllProducts();
                include 'views/admin/layout/header.php';
                include 'views/admin/products/list.php';
                include 'views/admin/layout/footer.php';
                break;
            case 'add':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $this->addProduct();
                }
                include 'views/admin/layout/header.php';
                include 'views/admin/products/add.php';
                include 'views/admin/layout/footer.php';
                break;
            case 'edit':
                $id = isset($_GET['id']) ? $_GET['id'] : 0;
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $this->updateProduct($id);
                }
                $product = $this->getProductById($id);
                include 'views/admin/layout/header.php';
                include 'views/admin/products/edit.php';
                include 'views/admin/layout/footer.php';
                break;
            case 'delete':
                $id = isset($_GET['id']) ? $_GET['id'] : 0;
                $this->deleteProduct($id);
                break;
            default:
                $products = $this->getAllProducts();
                include 'views/admin/layout/header.php';
                include 'views/admin/products/list.php';
                include 'views/admin/layout/footer.php';
                break;
        }
    }

    // Quản lý bài viết
    public function articles() {
        $sub_action = isset($_GET['sub']) ? $_GET['sub'] : 'list';
        $page_title = 'Quản lý bài viết';
        
        switch($sub_action) {
            case 'list':
                $articles = $this->getAllArticles();
                include 'views/admin/layout/header.php';
                include 'views/admin/articles/list.php';
                include 'views/admin/layout/footer.php';
                break;
            case 'add':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $this->addArticle();
                }
                include 'views/admin/layout/header.php';
                include 'views/admin/articles/add.php';
                include 'views/admin/layout/footer.php';
                break;
            case 'generateAI':
                $this->generateArticleAI();
                break;
            case 'edit':
                $id = isset($_GET['id']) ? $_GET['id'] : 0;
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $this->updateArticle($id);
                }
                $article = $this->getArticleById($id);
                include 'views/admin/layout/header.php';
                include 'views/admin/articles/edit.php';
                include 'views/admin/layout/footer.php';
                break;
            case 'delete':
                $id = isset($_GET['id']) ? $_GET['id'] : 0;
                $this->deleteArticle($id);
                break;
            default:
                $articles = $this->getAllArticles();
                include 'views/admin/layout/header.php';
                include 'views/admin/articles/list.php';
                include 'views/admin/layout/footer.php';
                break;
        }
    }

    // Quản lý đơn hàng
    public function orders() {
        $sub_action = isset($_GET['sub']) ? $_GET['sub'] : 'list';
        $page_title = 'Quản lý đơn hàng';
        
        switch($sub_action) {
            case 'list':
                $orders = $this->order->getAllOrders();
                include 'views/admin/layout/header.php';
                include 'views/admin/orders/list.php';
                include 'views/admin/layout/footer.php';
                break;
            case 'detail':
                $id = isset($_GET['id']) ? $_GET['id'] : 0;
                $order = $this->order->getOrderById($id);
                $order_items = $this->order->getOrderItems($id);
                include 'views/admin/layout/header.php';
                include 'views/admin/orders/detail.php';
                include 'views/admin/layout/footer.php';
                break;
            case 'updateStatus':
                $id = isset($_GET['id']) ? $_GET['id'] : 0;
                $status = isset($_POST['status']) ? $_POST['status'] : '';
                if ($status && $id) {
                    if ($this->order->updateOrderStatus($id, $status)) {
                        // Redirect về trang detail với thông báo thành công
                        header('Location: index.php?action=admin&page=orders&sub=detail&id=' . $id . '&success=1');
                    } else {
                        header('Location: index.php?action=admin&page=orders&sub=detail&id=' . $id . '&error=1');
                    }
                } else {
                    header('Location: index.php?action=admin&page=orders&sub=list');
                }
                exit();
                break;
            case 'cancel':
                $id = isset($_GET['id']) ? $_GET['id'] : 0;
                if ($id) {
                    if ($this->order->updateOrderStatus($id, 'cancelled')) {
                        header('Location: index.php?action=admin&page=orders&sub=detail&id=' . $id . '&success=2');
                    } else {
                        header('Location: index.php?action=admin&page=orders&sub=detail&id=' . $id . '&error=1');
                    }
                } else {
                    header('Location: index.php?action=admin&page=orders&sub=list');
                }
                exit();
                break;
            default:
                $orders = $this->order->getAllOrders();
                include 'views/admin/layout/header.php';
                include 'views/admin/orders/list.php';
                include 'views/admin/layout/footer.php';
                break;
        }
    }

    // Helper methods - Thống kê
    private function getTotalProducts() {
        $stmt = $this->product->getAllProducts();
        return $stmt->rowCount();
    }

    private function getTotalUsers() {
        $query = "SELECT COUNT(*) as total FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    private function getTotalOrders() {
        $orders = $this->order->getAllOrders();
        return count($orders);
    }

    private function getTotalRevenue() {
        $query = "SELECT SUM(total_amount) as total FROM orders WHERE status != 'cancelled'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ? $result['total'] : 0;
    }

    private function getPendingOrders() {
        $query = "SELECT COUNT(*) as total FROM orders WHERE status IN ('processing', 'waiting_shipping')";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    private function getTotalArticles() {
        $stmt = $this->article->getAllArticles();
        return $stmt->rowCount();
    }

    private function getTopProducts() {
        $query = "SELECT p.*, SUM(oi.quantity) as total_sold 
                  FROM products p 
                  LEFT JOIN order_items oi ON p.id = oi.product_id 
                  GROUP BY p.id 
                  ORDER BY total_sold DESC 
                  LIMIT 5";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Helper methods - Users
    private function getAllUsers() {
        $query = "SELECT * FROM users ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getUserById($id) {
        $this->user->getUserById($id);
        return [
            'id' => $this->user->id,
            'username' => $this->user->username,
            'email' => $this->user->email,
            'full_name' => $this->user->full_name,
            'phone' => $this->user->phone,
            'address' => $this->user->address,
            'role' => $this->user->role
        ];
    }

    private function updateUser($id) {
        $full_name = $_POST['full_name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $role = $_POST['role'];
        
        if ($this->user->updateProfile($id, $full_name, $phone, $address)) {
            // Cập nhật role
            $query = "UPDATE users SET role = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$role, $id]);
            
            header('Location: index.php?action=admin&page=users&sub=list');
        }
    }

    private function addUser() {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $full_name = $_POST['full_name'];
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $role = $_POST['role'] ?? 'user';
        
        if ($this->user->register($username, $email, $password, $full_name, $phone, $address)) {
            // Cập nhật role nếu là admin
            if ($role == 'admin') {
                $query = "UPDATE users SET role = 'admin' WHERE email = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$email]);
            }
            header('Location: index.php?action=admin&page=users&sub=list&success=1');
        } else {
            header('Location: index.php?action=admin&page=users&sub=add&error=1');
        }
    }

    private function deleteUser($id) {
        $query = "DELETE FROM users WHERE id = ? AND id != ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id, $_SESSION['user_id']]);
        header('Location: index.php?action=admin&page=users&sub=list');
    }

    // Helper methods - Products
    private function getAllProducts() {
        $stmt = $this->product->getAllProducts();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getProductById($id) {
        $this->product->getProductById($id);
        return [
            'id' => $this->product->id,
            'name' => $this->product->name,
            'description' => $this->product->description,
            'price' => $this->product->price,
            'image' => $this->product->image,
            'category' => $this->product->category
        ];
    }

    private function addProduct() {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $image = $_POST['image'];
        $category = $_POST['category'];
        
        $query = "INSERT INTO products (name, description, price, image, category, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$name, $description, $price, $image, $category]);
        
        header('Location: index.php?action=admin&page=products&sub=list');
    }

    private function updateProduct($id) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $image = $_POST['image'];
        $category = $_POST['category'];
        
        $query = "UPDATE products SET name = ?, description = ?, price = ?, image = ?, category = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$name, $description, $price, $image, $category, $id]);
        
        header('Location: index.php?action=admin&page=products&sub=list');
    }

    private function deleteProduct($id) {
        $query = "DELETE FROM products WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        header('Location: index.php?action=admin&page=products&sub=list');
    }

    // Helper methods - Articles
    private function getAllArticles() {
        $query = "SELECT * FROM articles ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getArticleById($id) {
        $query = "SELECT * FROM articles WHERE id = ? LIMIT 0,1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function addArticle() {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $excerpt = $_POST['excerpt'];
        $image = $_POST['image'];
        $category = $_POST['category'];
        $status = $_POST['status'];
        
        $query = "INSERT INTO articles (title, content, excerpt, image, author, category, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$title, $content, $excerpt, $image, $_SESSION['user_full_name'], $category, $status]);
        
        header('Location: index.php?action=admin&page=articles&sub=list');
    }

    private function updateArticle($id) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $excerpt = $_POST['excerpt'];
        $image = $_POST['image'];
        $category = $_POST['category'];
        $status = $_POST['status'];
        
        $query = "UPDATE articles SET title = ?, content = ?, excerpt = ?, image = ?, category = ?, status = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$title, $content, $excerpt, $image, $category, $status, $id]);
        
        header('Location: index.php?action=admin&page=articles&sub=list');
    }

    private function deleteArticle($id) {
        $query = "DELETE FROM articles WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        header('Location: index.php?action=admin&page=articles&sub=list');
    }

    // Tạo bài viết bằng AI
    private function generateArticleAI() {
        header('Content-Type: application/json');
        
        $topic = isset($_POST['topic']) ? $_POST['topic'] : '';
        $category = isset($_POST['category']) ? $_POST['category'] : 'Tin tức';
        $length = isset($_POST['length']) ? $_POST['length'] : 'medium';
        
        if (empty($topic)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập chủ đề bài viết']);
            exit;
        }
        
        $gemini = new GeminiAI();
        $result = $gemini->generateArticle($topic, $category, $length);
        
        echo json_encode($result);
        exit;
    }

    // Lấy doanh thu hàng tháng
    private function getMonthlyRevenue() {
        $query = "
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COALESCE(SUM(total_amount), 0) as revenue
            FROM orders
            WHERE status = 'completed'
            GROUP BY month
            ORDER BY month DESC
            LIMIT 12
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Đảo ngược để hiển thị từ cũ đến mới
        return array_reverse($results);
    }
}
?>

