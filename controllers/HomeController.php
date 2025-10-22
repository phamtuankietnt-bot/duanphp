<?php
require_once 'models/Product.php';
require_once 'models/Cart.php';
require_once 'models/Order.php';
require_once 'models/Article.php';
require_once 'models/User.php';
require_once 'models/Voucher.php';
require_once 'config/database.php';
require_once 'config/session.php';

class HomeController {
    private $product;
    private $cart;
    private $order;
    private $article;
    private $user;
    private $voucher;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->product = new Product($this->db);
        $this->cart = new Cart($this->db);
        $this->order = new Order($this->db);
        $this->article = new Article($this->db);
        $this->user = new User($this->db);
        $this->voucher = new Voucher($this->db);
    }

    public function index() {
        // Lấy sản phẩm mới nhất
        $stmt = $this->product->getAllProducts();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Lấy sản phẩm theo danh mục
        $sneakers = $this->product->getProductsByCategory('Sneakers');
        $boots = $this->product->getProductsByCategory('Boots');
        $sandals = $this->product->getProductsByCategory('Sandals');
        
        include 'views/home.php';
    }

    public function products() {
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        if (!empty($search)) {
            $stmt = $this->product->searchProducts($search);
        } elseif (!empty($category)) {
            $stmt = $this->product->getProductsByCategory($category);
        } else {
            $stmt = $this->product->getAllProducts();
        }
        
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'views/products.php';
    }

    public function productDetail() {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        
        if ($this->product->getProductById($id)) {
            $product = $this->product; // Truyền object product vào view
            include 'views/product_detail.php';
        } else {
            include 'views/404.php';
        }
    }

    public function about() {
        include 'views/about.php';
    }

    public function contact() {
        if ($_POST) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $message = $_POST['message'];
            
            // Xử lý gửi email hoặc lưu vào database
            $success = true;
        }
        
        include 'views/contact.php';
    }

    public function cart() {
        require_once 'config/session.php';
        startSession();
        
        // Khởi tạo giỏ hàng nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Làm sạch dữ liệu cart nếu có lỗi
        cleanCart();
        
        // Xử lý các action của giỏ hàng
        if ($_POST) {
            $action = isset($_POST['action']) ? $_POST['action'] : 'add';
            $product_id = (int)$_POST['product_id'];
            
            switch($action) {
                case 'add':
                    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
                    
                    // Kiểm tra product_id hợp lệ
                    if ($product_id <= 0) {
                        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                            header('Content-Type: application/json');
                            echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ']);
                            exit;
                        }
                        break;
                    }
                    
                    // Lấy thông tin sản phẩm
                    $product = $this->product->getProductById($product_id);
                    if (!$product) {
                        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                            header('Content-Type: application/json');
                            echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm']);
                            exit;
                        }
                        break;
                    }
                    
                    if (isset($_SESSION['cart'][$product_id])) {
                        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
                    } else {
                        $_SESSION['cart'][$product_id] = [
                            'product_id' => $product_id,
                            'name' => $this->product->name,
                            'price' => $this->product->price,
                            'image' => $this->product->image,
                            'category' => $this->product->category,
                            'quantity' => $quantity
                        ];
                    }
                    break;
                case 'update':
                    $quantity = (int)$_POST['quantity'];
                    if (isset($_SESSION['cart'][$product_id])) {
                        if ($quantity <= 0) {
                            unset($_SESSION['cart'][$product_id]);
                        } else {
                            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
                        }
                    }
                    break;
                case 'remove':
                    if (isset($_SESSION['cart'][$product_id])) {
                        unset($_SESSION['cart'][$product_id]);
                    }
                    break;
                case 'clear':
                    $_SESSION['cart'] = [];
                    break;
            }
            
            // Nếu là AJAX request, trả về JSON
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Cập nhật giỏ hàng thành công']);
                exit;
            }
            
            // Nếu là fetch API request (không có HTTP_X_REQUESTED_WITH header)
            if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/x-www-form-urlencoded') !== false) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm vào giỏ hàng thành công']);
                exit;
            }
            
            // Redirect để tránh resubmit
            header('Location: index.php?action=cart');
            exit;
        }
        
        // Lấy danh sách sản phẩm trong giỏ hàng
        $cart_items = $_SESSION['cart'] ?? [];
        $total_amount = 0;
        $cart_count = 0;
        
        foreach ($cart_items as $item) {
            if (is_array($item) && isset($item['price']) && isset($item['quantity'])) {
                $total_amount += (float)$item['price'] * (int)$item['quantity'];
                $cart_count += (int)$item['quantity'];
            }
        }
        
        // Lấy tất cả voucher phù hợp với đơn hàng
        $vouchers = $this->voucher->getActiveVouchers($total_amount);
        
        // Nếu là AJAX request để lấy cart count
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !$_POST) {
            header('Content-Type: application/json');
            echo json_encode(['cart_count' => $cart_count]);
            exit;
        }
        
        // Nếu là AJAX request để lấy voucher
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && 
            isset($_POST['action']) && $_POST['action'] == 'get_vouchers') {
            $requested_total = isset($_POST['total_amount']) ? (float)$_POST['total_amount'] : $total_amount;
            $vouchers = $this->voucher->getActiveVouchers($requested_total);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'vouchers' => $vouchers]);
            exit;
        }
        
        include 'views/cart.php';
    }

    public function checkout() {
        require_once 'config/session.php';
        startSession();
        
        // Kiểm tra giỏ hàng có sản phẩm không
        $cart_items = $_SESSION['cart'] ?? [];
        if (empty($cart_items)) {
            header('Location: index.php?action=cart');
            exit;
        }


        $total_amount = 0;
        $cart_count = 0;
        
        foreach ($cart_items as $item) {
            if (is_array($item) && isset($item['price']) && isset($item['quantity'])) {
                $total_amount += (float)$item['price'] * (int)$item['quantity'];
                $cart_count += (int)$item['quantity'];
            }
        }

        // Xử lý form thanh toán
        if ($_POST) {
            // Validation
            $errors = [];
            
            if (empty($_POST['customer_name'])) {
                $errors[] = 'Vui lòng nhập họ và tên';
            }
            
            if (empty($_POST['customer_email']) || !filter_var($_POST['customer_email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Vui lòng nhập email hợp lệ';
            }
            
            if (empty($_POST['customer_phone'])) {
                $errors[] = 'Vui lòng nhập số điện thoại';
            }
            
            // Kiểm tra địa chỉ từ các trường riêng biệt
            $province = trim($_POST['province'] ?? '');
            $district = trim($_POST['district'] ?? '');
            $ward = trim($_POST['ward'] ?? '');
            $street = trim($_POST['street_address'] ?? '');
            
            if (empty($province)) {
                $errors[] = 'Vui lòng chọn tỉnh/thành phố';
            }
            if (empty($district)) {
                $errors[] = 'Vui lòng chọn quận/huyện';
            }
            if (empty($ward)) {
                $errors[] = 'Vui lòng chọn phường/xã';
            }
            if (empty($street) || strlen($street) < 5) {
                $errors[] = 'Vui lòng nhập số nhà, tên đường (ít nhất 5 ký tự)';
            }
            
            $address = '';
            if (!empty($province) && !empty($district) && !empty($ward) && !empty($street)) {
                $address = $street . ', ' . $ward . ', ' . $district . ', ' . $province;
            }
            
            if (empty($_POST['payment_method'])) {
                $errors[] = 'Vui lòng chọn phương thức thanh toán';
            }
            
            if (!empty($errors)) {
                $error = implode('<br>', $errors);
            } else {
                $customer_info = [
                    'name' => trim($_POST['customer_name']),
                    'email' => trim($_POST['customer_email']),
                    'phone' => trim($_POST['customer_phone']),
                    'address' => $address,
                    'payment_method' => $_POST['payment_method']
                ];

                // Tạo đơn hàng
                $order_id = $this->order->createOrder($customer_info, $cart_items, $total_amount);
                
                if ($order_id) {
                    // Xóa giỏ hàng sau khi tạo đơn hàng thành công
                    $_SESSION['cart'] = [];
                    
                    // Chuyển đến trang thành công
                    header('Location: index.php?action=orderSuccess&order_id=' . $order_id);
                    exit;
                } else {
                    $error = "Có lỗi xảy ra khi tạo đơn hàng. Vui lòng thử lại.";
                }
            }
        }

        include 'views/checkout.php';
    }

    public function orderSuccess() {
        $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 0;
        
        if ($order_id) {
            $order = $this->order->getOrderById($order_id);
            $order_items = $this->order->getOrderItems($order_id);
        } else {
            $order = null;
            $order_items = [];
        }

        include 'views/order_success.php';
    }

    public function articles() {
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        if (!empty($search)) {
            $stmt = $this->article->searchArticles($search);
        } elseif (!empty($category)) {
            $stmt = $this->article->getArticlesByCategory($category);
        } else {
            $stmt = $this->article->getAllArticles();
        }
        
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'views/articles.php';
    }

    public function articleDetail() {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        
        if ($this->article->getArticleById($id)) {
            $article = $this->article;
            
            // Lấy bài viết liên quan
            $related_stmt = $this->article->getRelatedArticles($article->category, $article->id, 3);
            $related_articles = $related_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            include 'views/article_detail.php';
        } else {
            include 'views/404.php';
        }
    }

    public function login() {
        $error = '';
        $success = '';

        if ($_POST) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            if ($this->user->login($email, $password)) {
                // Lưu thông tin user vào session
                require_once 'config/session.php';
                startSession();
                $_SESSION['user_id'] = $this->user->id;
                $_SESSION['username'] = $this->user->username;
                $_SESSION['email'] = $this->user->email;
                $_SESSION['full_name'] = $this->user->full_name;
                $_SESSION['role'] = $this->user->role;
                
                // Redirect về trang trước đó hoặc trang chủ
                $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
                header('Location: ' . $redirect);
                exit;
            } else {
                $error = 'Email hoặc mật khẩu không đúng!';
            }
        }

        include 'views/login.php';
    }

    public function register() {
        $error = '';
        $success = '';

        if ($_POST) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $full_name = $_POST['full_name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            // Validation
            if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
                $error = 'Vui lòng điền đầy đủ thông tin bắt buộc!';
            } elseif ($password !== $confirm_password) {
                $error = 'Mật khẩu xác nhận không khớp!';
            } elseif (strlen($password) < 6) {
                $error = 'Mật khẩu phải có ít nhất 6 ký tự!';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Email không hợp lệ!';
            } else {
                if ($this->user->register($username, $email, $password, $full_name, $phone, $address)) {
                    $success = 'Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.';
                } else {
                    $error = 'Email hoặc tên đăng nhập đã tồn tại!';
                }
            }
        }

        include 'views/register.php';
    }

    public function logout() {
        require_once 'config/session.php';
        startSession();
        session_destroy();
        header('Location: index.php');
        exit;
    }

    public function profile() {
        require_once 'config/session.php';
        startSession();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $this->user->getUserById($user_id);
        $user = $this->user;

        $error = '';
        $success = '';

        if ($_POST) {
            $full_name = $_POST['full_name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            if ($this->user->updateProfile($user_id, $full_name, $phone, $address)) {
                $success = 'Cập nhật thông tin thành công!';
                $_SESSION['full_name'] = $full_name;
                $user->full_name = $full_name;
                $user->phone = $phone;
                $user->address = $address;
            } else {
                $error = 'Có lỗi xảy ra khi cập nhật thông tin!';
            }
        }

        include 'views/profile.php';
    }

    public function orders() {
        startSession();
        
        // Kiểm tra user đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login&redirect=' . urlencode('index.php?action=orders'));
            exit;
        }
        
        // Lấy danh sách đơn hàng của user
        $user_id = $_SESSION['user_id'];
        $orders = $this->order->getOrdersByUserId($user_id);
        
        include 'views/orders.php';
    }

    public function orderDetail() {
        startSession();
        
        // Kiểm tra user đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login&redirect=' . urlencode('index.php?action=orderDetail&id=' . $_GET['id']));
            exit;
        }
        
        // Lấy ID đơn hàng từ URL
        $order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($order_id <= 0) {
            header('Location: index.php?action=orders');
            exit;
        }
        
        // Lấy thông tin đơn hàng
        $order = $this->order->getOrderById($order_id);
        
        if (!$order) {
            header('Location: index.php?action=orders');
            exit;
        }
        
        // Kiểm tra đơn hàng thuộc về user hiện tại
        $user_id = $_SESSION['user_id'];
        $user_orders = $this->order->getOrdersByUserId($user_id);
        $order_belongs_to_user = false;
        
        foreach ($user_orders as $user_order) {
            if ($user_order['id'] == $order_id) {
                $order_belongs_to_user = true;
                break;
            }
        }
        
        if (!$order_belongs_to_user) {
            header('Location: index.php?action=orders');
            exit;
        }
        
        // Lấy chi tiết đơn hàng
        $order_items = $this->order->getOrderItems($order_id);
        
        include 'views/order_detail.php';
    }

    public function cancelOrder() {
        startSession();
        
        // Kiểm tra user đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập!']);
            exit;
        }
        
        // Kiểm tra method POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Method không được phép!']);
            exit;
        }
        
        $order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
        
        if ($order_id <= 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'ID đơn hàng không hợp lệ!']);
            exit;
        }
        
        // Lấy thông tin đơn hàng
        $order = $this->order->getOrderById($order_id);
        
        if (!$order) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng!']);
            exit;
        }
        
        // Kiểm tra đơn hàng thuộc về user hiện tại
        $user_id = $_SESSION['user_id'];
        $user_orders = $this->order->getOrdersByUserId($user_id);
        $order_belongs_to_user = false;
        
        foreach ($user_orders as $user_order) {
            if ($user_order['id'] == $order_id) {
                $order_belongs_to_user = true;
                break;
            }
        }
        
        if (!$order_belongs_to_user) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền hủy đơn hàng này!']);
            exit;
        }
        
        // Kiểm tra trạng thái đơn hàng
        if ($order['status'] !== 'processing') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Chỉ có thể hủy đơn hàng đang xử lý!']);
            exit;
        }
        
        // Cập nhật trạng thái đơn hàng
        $result = $this->order->updateOrderStatus($order_id, 'cancelled');
        
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Đã hủy đơn hàng thành công!']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi hủy đơn hàng!']);
        }
    }

}
?>
