<div class="content-box">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div style="background: #27ae60; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <strong>✓ Thành công!</strong> Đã thêm người dùng mới.
        </div>
    <?php endif; ?>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #2c3e50;">Danh sách người dùng</h2>
        <a href="index.php?action=admin&page=users&sub=add" class="btn btn-success">+ Thêm người dùng</a>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên đăng nhập</th>
                <th>Email</th>
                <th>Họ tên</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
                <th>Vai trò</th>
                <th>Ngày đăng ký</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($users) > 0): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($user['address'] ?? 'N/A'); ?></td>
                        <td>
                            <span style="padding: 5px 10px; background: <?php echo $user['role'] == 'admin' ? '#e74c3c' : '#3498db'; ?>; color: white; border-radius: 5px; font-size: 12px;">
                                <?php echo $user['role'] == 'admin' ? 'Admin' : 'User'; ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <a href="index.php?action=admin&page=users&sub=edit&id=<?php echo $user['id']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px; margin-right: 5px;">Sửa</a>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <a href="index.php?action=admin&page=users&sub=delete&id=<?php echo $user['id']; ?>" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">Xóa</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align: center; color: #999;">Chưa có người dùng nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

