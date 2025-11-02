<?php
// Script tạo/reset password admin
require_once 'config/database.php';
require_once 'models/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

echo "<h1>🔧 Admin Setup Tool</h1>";

// Password mặc định cho admin
$admin_email = 'admin@shoestore.com';
$admin_password = 'admin123'; // Password mặc định

// Kiểm tra xem admin đã tồn tại chưa
if ($user->emailExists($admin_email)) {
    echo "<p>Đang cập nhật mật khẩu admin...</p>";
    
    // Cập nhật password
    $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $db->prepare($query);
    
    if ($stmt->execute([$hashed_password, $admin_email])) {
        echo "<p style='color: green;'>✓ Đã cập nhật mật khẩu admin thành công!</p>";
    } else {
        echo "<p style='color: red;'>✗ Lỗi khi cập nhật mật khẩu</p>";
    }
} else {
    echo "<p>Đang tạo tài khoản admin...</p>";
    
    // Tạo admin mới
    if ($user->register('admin', $admin_email, $admin_password, 'Quản trị viên', '0123456789', '123 Đường ABC, Quận 1, TP.HCM')) {
        // Cập nhật role thành admin
        $query = "UPDATE users SET role = 'admin' WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$admin_email]);
        
        echo "<p style='color: green;'>✓ Đã tạo tài khoản admin thành công!</p>";
    } else {
        echo "<p style='color: red;'>✗ Lỗi khi tạo tài khoản admin</p>";
    }
}

echo "<hr>";
echo "<h2>📋 Thông tin đăng nhập Admin:</h2>";
echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
echo "<p><strong>Email:</strong> <code style='background: white; padding: 5px 10px; border-radius: 5px;'>admin@shoestore.com</code></p>";
echo "<p><strong>Password:</strong> <code style='background: white; padding: 5px 10px; border-radius: 5px;'>admin123</code></p>";
echo "</div>";

echo "<h3>🚀 Các bước tiếp theo:</h3>";
echo "<ol>";
echo "<li>Mở trang đăng nhập: <a href='index.php?action=login' target='_blank'>index.php?action=login</a></li>";
echo "<li>Nhập email: <strong>admin@shoestore.com</strong></li>";
echo "<li>Nhập password: <strong>admin123</strong></li>";
echo "<li>Sau khi đăng nhập, truy cập admin panel: <a href='index.php?action=admin&page=dashboard' target='_blank'>index.php?action=admin&page=dashboard</a></li>";
echo "</ol>";

echo "<hr>";
echo "<p style='color: orange;'><strong>⚠ Lưu ý:</strong> Sau khi đăng nhập thành công, bạn nên đổi mật khẩu trong trang admin để bảo mật.</p>";
?>

