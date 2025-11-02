<?php
// File để kiểm tra và tạo bảng database
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo "<h2>Kết nối database thành công!</h2>";
        
        // Kiểm tra các bảng
        $tables = ['categories', 'products', 'cart_items', 'orders', 'order_items', 'favorites', 'users', 'articles'];
        
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
        
        // Tạo bảng categories
        $create_categories = "CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL UNIQUE,
            slug VARCHAR(100) NOT NULL UNIQUE,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        if ($db->exec($create_categories)) {
            echo "<p style='color: green;'>✓ Đã tạo bảng 'categories'</p>";
        }
        
        // Tạo bảng products với category_id
        $create_products = "CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            image VARCHAR(500),
            category_id INT NOT NULL,
            stock INT NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
        )";
        
        if ($db->exec($create_products)) {
            echo "<p style='color: green;'>✓ Đã tạo bảng 'products'</p>";
        }
        
        // Tạo bảng users
        $create_users = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            full_name VARCHAR(100) NOT NULL,
            phone VARCHAR(20),
            address TEXT,
            role ENUM('user', 'admin') DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        if ($db->exec($create_users)) {
            echo "<p style='color: green;'>✓ Đã tạo bảng 'users'</p>";
        }
        
        // Tạo bảng cart_items với user_id
        $create_cart_items = "CREATE TABLE IF NOT EXISTS cart_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            product_id INT NOT NULL,
            quantity INT NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        )";
        
        if ($db->exec($create_cart_items)) {
            echo "<p style='color: green;'>✓ Đã tạo bảng 'cart_items'</p>";
        }
        
        // Tạo bảng orders với user_id
        $create_orders = "CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            customer_name VARCHAR(255) NOT NULL,
            customer_email VARCHAR(255) NOT NULL,
            customer_phone VARCHAR(20) NOT NULL,
            customer_address TEXT NOT NULL,
            payment_method VARCHAR(50) NOT NULL,
            total_amount DECIMAL(10,2) NOT NULL,
            status ENUM('processing', 'waiting_shipping', 'shipping', 'completed', 'cancelled') DEFAULT 'processing',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
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
        
        // Tạo bảng favorites
        $create_favorites = "CREATE TABLE IF NOT EXISTS favorites (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            product_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
            UNIQUE KEY unique_favorite (user_id, product_id)
        )";
        
        if ($db->exec($create_favorites)) {
            echo "<p style='color: green;'>✓ Đã tạo bảng 'favorites'</p>";
        }
        
        // Tạo bảng articles
        $create_articles = "CREATE TABLE IF NOT EXISTS articles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            excerpt TEXT,
            image VARCHAR(500),
            author VARCHAR(100) NOT NULL,
            category VARCHAR(50) NOT NULL,
            status ENUM('draft', 'published') DEFAULT 'published',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        if ($db->exec($create_articles)) {
            echo "<p style='color: green;'>✓ Đã tạo bảng 'articles'</p>";
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