<?php
// File để kiểm tra và tạo bảng database
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo "<h2>Kết nối database thành công!</h2>";
        
        // Kiểm tra các bảng
        $tables = ['products', 'cart_items', 'orders', 'order_items'];
        
        foreach ($tables as $table) {
            $query = "SHOW TABLES LIKE '$table'";
            $stmt = $db->prepare($query);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                echo "<p style='color: green;'>✓ Bảng '$table' đã tồn tại</p>";
            } else {
                echo "<p style='color: red;'>✗ Bảng '$table' chưa tồn tại</p>";
            }
        }
        
        // Tạo bảng nếu chưa có
        echo "<h3>Tạo bảng nếu chưa có:</h3>";
        
        // Tạo bảng orders
        $create_orders = "CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            customer_name VARCHAR(255) NOT NULL,
            customer_email VARCHAR(255) NOT NULL,
            customer_phone VARCHAR(20) NOT NULL,
            customer_address TEXT NOT NULL,
            payment_method VARCHAR(50) NOT NULL,
            total_amount DECIMAL(10,2) NOT NULL,
            status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        if ($db->exec($create_orders)) {
            echo "<p style='color: green;'>✓ Đã tạo bảng 'orders'</p>";
        }
        
        // Tạo bảng order_items
        $create_order_items = "CREATE TABLE IF NOT EXISTS order_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            product_id INT NOT NULL,
            product_name VARCHAR(255) NOT NULL,
            product_price DECIMAL(10,2) NOT NULL,
            quantity INT NOT NULL,
            total_price DECIMAL(10,2) NOT NULL,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        )";
        
        if ($db->exec($create_order_items)) {
            echo "<p style='color: green;'>✓ Đã tạo bảng 'order_items'</p>";
        }
        
        echo "<h3>Hoàn thành! Bây giờ bạn có thể:</h3>";
        echo "<p><a href='index.php'>Về trang chủ</a></p>";
        echo "<p><a href='index.php?action=cart'>Vào giỏ hàng</a></p>";
        
    } else {
        echo "<h2 style='color: red;'>Lỗi kết nối database!</h2>";
    }
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>Lỗi: " . $e->getMessage() . "</h2>";
}
?>
