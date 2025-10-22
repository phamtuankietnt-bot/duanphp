# Trang web bán giày - ShoeStore

Đây là một trang web bán giày đơn giản được xây dựng bằng PHP theo mô hình MVC.

## Tính năng

- **Trang chủ**: Hiển thị sản phẩm nổi bật và danh mục
- **Sản phẩm**: Danh sách sản phẩm với bộ lọc theo danh mục
- **Chi tiết sản phẩm**: Thông tin chi tiết về từng sản phẩm
- **Tìm kiếm**: Tìm kiếm sản phẩm theo tên hoặc mô tả
- **Giới thiệu**: Thông tin về cửa hàng
- **Liên hệ**: Form liên hệ và thông tin liên hệ

## Cấu trúc thư mục

```
duanmau/
├── assets/
│   └── css/
│       └── style.css
├── config/
│   └── database.php
├── controllers/
│   └── HomeController.php
├── models/
│   └── Product.php
├── views/
│   ├── layout/
│   │   ├── header.php
│   │   └── footer.php
│   ├── home.php
│   ├── products.php
│   ├── product_detail.php
│   ├── about.php
│   ├── contact.php
│   └── 404.php
├── index.php
├── database.sql
└── README.md
```

## Cài đặt

1. **Cài đặt XAMPP** (đã có sẵn)
2. **Tạo database**:
   - Mở phpMyAdmin (http://localhost/phpmyadmin)
   - Import file `database.sql` để tạo database và dữ liệu mẫu
3. **Cấu hình database**:
   - Mở file `config/database.php`
   - Kiểm tra thông tin kết nối database (mặc định đã đúng cho XAMPP)
4. **Truy cập website**:
   - Mở trình duyệt và truy cập: `http://localhost/duanmau`

## Cấu hình database

Mặc định sử dụng:
- Host: localhost
- Database: shoe_store
- Username: root
- Password: (để trống)

## Các trang chính

- **Trang chủ**: `http://localhost/duanmau/`
- **Sản phẩm**: `http://localhost/duanmau/?action=products`
- **Giới thiệu**: `http://localhost/duanmau/?action=about`
- **Liên hệ**: `http://localhost/duanmau/?action=contact`

## Mô hình MVC

- **Model**: `models/Product.php` - Xử lý dữ liệu sản phẩm
- **View**: `views/` - Giao diện người dùng
- **Controller**: `controllers/HomeController.php` - Xử lý logic và điều khiển

## Công nghệ sử dụng

- PHP 7.4+
- MySQL
- HTML5
- CSS3
- Font Awesome (icons)
- PDO (PHP Data Objects)

## Ghi chú

- Website sử dụng placeholder images từ via.placeholder.com
- Có thể thay thế bằng hình ảnh thực tế
- Responsive design, tương thích mobile
