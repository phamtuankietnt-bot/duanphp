<?php 
$page_title = 'Tài khoản - ShoeStore';
include 'views/layout/header.php'; 
?>

<main class="main">
    <div class="container">
        <div class="page-header">
            <h1>Tài khoản của tôi</h1>
            <div class="breadcrumb">
                <a href="index.php">Trang chủ</a> > <span>Tài khoản</span>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-sidebar">
                <div class="profile-avatar">
                    <i class="fas fa-user-circle"></i>
                    <h3><?php echo htmlspecialchars($user->full_name); ?></h3>
                    <p><?php echo htmlspecialchars($user->email); ?></p>
                </div>
                <nav class="profile-nav">
                    <a href="#profile-info" class="active">Thông tin cá nhân</a>
                    <a href="#orders">Đơn hàng</a>
                    <a href="#addresses">Địa chỉ</a>
                    <a href="#security">Bảo mật</a>
                </nav>
            </div>

            <div class="profile-main">
                <div class="profile-section" id="profile-info">
                    <h2>Thông tin cá nhân</h2>
                    
                    <?php if (isset($error) && $error): ?>
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <p><?php echo $error; ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($success) && $success): ?>
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i>
                            <p><?php echo $success; ?></p>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?action=profile" class="profile-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="username">Tên đăng nhập</label>
                                <input type="text" id="username" value="<?php echo htmlspecialchars($user->username); ?>" disabled>
                                <small>Không thể thay đổi tên đăng nhập</small>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" value="<?php echo htmlspecialchars($user->email); ?>" disabled>
                                <small>Không thể thay đổi email</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="full_name">Họ và tên *</label>
                            <input type="text" id="full_name" name="full_name" required 
                                   value="<?php echo htmlspecialchars($user->full_name); ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="tel" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($user->phone); ?>">
                            </div>
                            <div class="form-group">
                                <label for="role">Vai trò</label>
                                <input type="text" id="role" value="<?php echo ucfirst($user->role); ?>" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($user->address); ?></textarea>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
                        </div>
                    </form>
                </div>

                <div class="profile-section" id="orders">
                    <h2>Đơn hàng của tôi</h2>
                    <div class="empty-state">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>Chưa có đơn hàng nào</h3>
                        <p>Bạn chưa có đơn hàng nào. Hãy bắt đầu mua sắm ngay!</p>
                        <a href="index.php?action=products" class="btn btn-primary">Mua sắm ngay</a>
                    </div>
                </div>

                <div class="profile-section" id="addresses">
                    <h2>Địa chỉ giao hàng</h2>
                    <div class="address-card">
                        <div class="address-info">
                            <h4>Địa chỉ mặc định</h4>
                            <p><?php echo htmlspecialchars($user->address ?: 'Chưa có địa chỉ'); ?></p>
                            <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($user->phone ?: 'Chưa có số điện thoại'); ?></p>
                        </div>
                        <div class="address-actions">
                            <button class="btn btn-secondary btn-sm">Chỉnh sửa</button>
                        </div>
                    </div>
                </div>

                <div class="profile-section" id="security">
                    <h2>Bảo mật tài khoản</h2>
                    <div class="security-info">
                        <div class="security-item">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <h4>Mật khẩu</h4>
                                <p>Mật khẩu của bạn đã được bảo mật</p>
                            </div>
                            <button class="btn btn-secondary btn-sm">Đổi mật khẩu</button>
                        </div>
                        <div class="security-item">
                            <i class="fas fa-calendar"></i>
                            <div>
                                <h4>Ngày tham gia</h4>
                                <p><?php echo date('d/m/Y', strtotime($user->created_at)); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'views/layout/footer.php'; ?>
