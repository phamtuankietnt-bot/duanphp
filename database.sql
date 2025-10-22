-- Tạo database
CREATE DATABASE IF NOT EXISTS shoe_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Sử dụng database
USE shoe_store;

-- Tạo bảng products
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(500),
    category VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tạo bảng cart_items
CREATE TABLE IF NOT EXISTS cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Tạo bảng orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_address TEXT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('processing', 'waiting_shipping', 'shipping', 'completed', 'cancelled') DEFAULT 'processing',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tạo bảng order_items
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    product_price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Tạo bảng articles
CREATE TABLE IF NOT EXISTS articles (
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
);

-- Tạo bảng users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Thêm dữ liệu mẫu
INSERT INTO products (name, description, price, image, category) VALUES
('Giày thể thao Nike Air Max', 'Giày thể thao Nike Air Max với thiết kế hiện đại, chất liệu cao cấp, thoải mái khi sử dụng.', 2500000, 'https://picsum.photos/300/300?random=1', 'Sneakers'),
('Giày thể thao Adidas Ultraboost', 'Giày chạy bộ Adidas Ultraboost với công nghệ Boost tiên tiến, đệm êm ái.', 3200000, 'https://picsum.photos/300/300?random=2', 'Sneakers'),
('Giày boot da nam Timberland', 'Giày boot da nam Timberland chính hãng, bền đẹp, phù hợp cho mọi hoạt động ngoài trời.', 4500000, 'https://picsum.photos/300/300?random=3', 'Boots'),
('Giày boot nữ Dr. Martens', 'Giày boot nữ Dr. Martens với thiết kế cá tính, chất liệu da cao cấp.', 3800000, 'https://picsum.photos/300/300?random=4', 'Boots'),
('Dép sandal nữ Birkenstock', 'Dép sandal nữ Birkenstock với đế êm ái, thiết kế thời trang, dễ phối đồ.', 1200000, 'https://picsum.photos/300/300?random=5', 'Sandals'),
('Dép sandal nam Crocs', 'Dép sandal nam Crocs với chất liệu nhẹ, thoáng khí, dễ vệ sinh.', 800000, 'https://picsum.photos/300/300?random=6', 'Sandals'),
('Giày thể thao Converse Chuck Taylor', 'Giày thể thao Converse Chuck Taylor cổ điển, dễ phối đồ, phù hợp mọi lứa tuổi.', 1500000, 'https://picsum.photos/300/300?random=7', 'Sneakers'),
('Giày thể thao Vans Old Skool', 'Giày thể thao Vans Old Skool với thiết kế skateboard, chất liệu canvas bền đẹp.', 1800000, 'https://picsum.photos/300/300?random=8', 'Sneakers'),
('Giày boot nữ UGG', 'Giày boot nữ UGG với lông cừu ấm áp, thiết kế đẹp, phù hợp mùa đông.', 3500000, 'https://picsum.photos/300/300?random=9', 'Boots'),
('Dép sandal nữ Havaianas', 'Dép sandal nữ Havaianas với thiết kế Brazil, màu sắc đa dạng, giá cả hợp lý.', 600000, 'https://picsum.photos/300/300?random=10', 'Sandals'),
('Giày thể thao Puma RS-X', 'Giày thể thao Puma RS-X với thiết kế futuristic, công nghệ tiên tiến.', 2200000, 'https://picsum.photos/300/300?random=11', 'Sneakers'),
('Giày boot nam Red Wing', 'Giày boot nam Red Wing với chất liệu da bò cao cấp, thiết kế cổ điển.', 5500000, 'https://picsum.photos/300/300?random=12', 'Boots');

-- Thêm dữ liệu mẫu cho articles
INSERT INTO articles (title, content, excerpt, image, author, category) VALUES
('Xu hướng giày thể thao 2024: Những mẫu giày hot nhất', 'Năm 2024 đánh dấu sự bùng nổ của các xu hướng giày thể thao mới với thiết kế đột phá và công nghệ tiên tiến. Từ Nike Air Max đến Adidas Ultraboost, các thương hiệu lớn đều mang đến những sản phẩm ấn tượng...', 'Khám phá những xu hướng giày thể thao hot nhất năm 2024 với thiết kế đột phá và công nghệ tiên tiến.', 'https://picsum.photos/600/400?random=13', 'Admin', 'Xu hướng'),
('Hướng dẫn chọn giày chạy bộ phù hợp với chân', 'Việc chọn giày chạy bộ phù hợp rất quan trọng để tránh chấn thương và tăng hiệu quả tập luyện. Bài viết này sẽ hướng dẫn bạn cách chọn giày dựa trên kiểu chân, mục đích sử dụng...', 'Hướng dẫn chi tiết cách chọn giày chạy bộ phù hợp với kiểu chân và mục đích sử dụng của bạn.', 'https://picsum.photos/600/400?random=14', 'Admin', 'Hướng dẫn'),
('Review chi tiết Nike Air Max 270: Có đáng mua không?', 'Nike Air Max 270 là một trong những mẫu giày được yêu thích nhất hiện nay. Trong bài review này, chúng tôi sẽ đánh giá chi tiết về thiết kế, độ thoải mái, độ bền và giá trị của đôi giày này...', 'Review chi tiết Nike Air Max 270 với đánh giá về thiết kế, độ thoải mái và giá trị sử dụng.', 'https://picsum.photos/600/400?random=15', 'Admin', 'Review'),
('Lịch sử phát triển của giày thể thao qua các thập kỷ', 'Từ những đôi giày đơn giản đầu thế kỷ 20 đến những mẫu giày công nghệ cao ngày nay, lịch sử giày thể thao đã trải qua nhiều thay đổi đáng kể...', 'Khám phá lịch sử phát triển của giày thể thao từ những ngày đầu đến hiện tại.', 'https://picsum.photos/600/400?random=16', 'Admin', 'Tin tức'),
('Cách bảo quản giày da đúng cách để tăng tuổi thọ', 'Giày da là một khoản đầu tư đáng kể, vì vậy việc bảo quản đúng cách sẽ giúp tăng tuổi thọ và giữ được vẻ đẹp của đôi giày. Dưới đây là những mẹo bảo quản giày da hiệu quả...', 'Những mẹo bảo quản giày da đúng cách để tăng tuổi thọ và giữ vẻ đẹp của đôi giày.', 'https://picsum.photos/600/400?random=17', 'Admin', 'Hướng dẫn'),
('Top 5 thương hiệu giày cao cấp được ưa chuộng nhất', 'Thị trường giày cao cấp luôn sôi động với sự cạnh tranh khốc liệt giữa các thương hiệu. Dưới đây là top 5 thương hiệu giày cao cấp được ưa chuộng nhất hiện nay...', 'Khám phá top 5 thương hiệu giày cao cấp được ưa chuộng nhất trên thị trường hiện nay.', 'https://picsum.photos/600/400?random=18', 'Admin', 'Tin tức');

-- Thêm tài khoản admin mẫu
INSERT INTO users (username, email, password, full_name, phone, address, role) VALUES
('admin', 'admin@shoestore.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Quản trị viên', '0123456789', '123 Đường ABC, Quận 1, TP.HCM', 'admin');

-- Thêm dữ liệu mẫu cho orders với 5 trạng thái
INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, payment_method, total_amount, status) VALUES
('Nguyễn Văn A', 'nguyenvana@email.com', '0123456789', '123 Đường ABC, Quận 1, TP.HCM', 'cod', 1500000.00, 'processing'),
('Trần Thị B', 'tranthib@email.com', '0987654321', '456 Đường XYZ, Quận 2, TP.HCM', 'bank_transfer', 2300000.00, 'waiting_shipping'),
('Lê Văn C', 'levanc@email.com', '0369852147', '789 Đường DEF, Quận 3, TP.HCM', 'momo', 3200000.00, 'shipping'),
('Phạm Thị D', 'phamthid@email.com', '0741258963', '321 Đường GHI, Quận 4, TP.HCM', 'zalopay', 1800000.00, 'completed'),
('Hoàng Văn E', 'hoangvane@email.com', '0852147369', '654 Đường JKL, Quận 5, TP.HCM', 'cod', 2500000.00, 'cancelled');
