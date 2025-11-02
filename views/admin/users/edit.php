<div class="content-box">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">Chỉnh sửa người dùng</h2>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Tên đăng nhập</label>
            <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
        </div>
        
        <div class="form-group">
            <label>Email</label>
            <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
        </div>
        
        <div class="form-group">
            <label>Họ tên *</label>
            <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Số điện thoại</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label>Địa chỉ</label>
            <textarea name="address"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Vai trò *</label>
            <select name="role" required>
                <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="index.php?action=admin&page=users&sub=list" class="btn" style="background: #95a5a6; color: white;">Hủy</a>
    </form>
</div>

