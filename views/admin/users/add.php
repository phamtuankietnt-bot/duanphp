<div class="content-box">
    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
        <div style="background: #e74c3c; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <strong>✗ Lỗi!</strong> Username hoặc Email đã tồn tại. Vui lòng thử lại.
        </div>
    <?php endif; ?>
    
    <h2 style="margin-bottom: 20px; color: #2c3e50;">Thêm người dùng mới</h2>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Tên đăng nhập *</label>
            <input type="text" name="username" required>
        </div>
        
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label>Mật khẩu *</label>
            <input type="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label>Họ tên *</label>
            <input type="text" name="full_name" required>
        </div>
        
        <div class="form-group">
            <label>Số điện thoại</label>
            <input type="text" name="phone">
        </div>
        
        <div class="form-group">
            <label>Địa chỉ</label>
            <textarea name="address"></textarea>
        </div>
        
        <div class="form-group">
            <label>Vai trò *</label>
            <select name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Thêm người dùng</button>
        <a href="index.php?action=admin&page=users&sub=list" class="btn" style="background: #95a5a6; color: white;">Hủy</a>
    </form>
</div>

