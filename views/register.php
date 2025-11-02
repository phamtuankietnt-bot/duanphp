<?php 
$page_title = 'Đăng ký - ShoeStore';
include 'views/layout/header.php'; 
?>

<main class="main">
    <div class="container">
        <div class="auth-page">
            <div class="auth-container">
                <div class="auth-header">
                    <h1>Đăng ký tài khoản</h1>
                    <p>Tạo tài khoản để trải nghiệm mua sắm tốt nhất</p>
                </div>

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

                <form method="POST" action="index.php?action=register" class="auth-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="username">Tên đăng nhập *</label>
                            <div class="input-group">
                                <i class="fas fa-user"></i>
                                <input type="text" id="username" name="username" required 
                                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <div class="input-group">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="email" name="email" required 
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="full_name">Họ và tên *</label>
                        <div class="input-group">
                            <i class="fas fa-id-card"></i>
                            <input type="text" id="full_name" name="full_name" required 
                                   value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Mật khẩu *</label>
                            <div class="input-group">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="password" name="password" required>
                                <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Xác nhận mật khẩu *</label>
                            <div class="input-group">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="confirm_password" name="confirm_password" required>
                                <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <div class="input-group">
                                <i class="fas fa-phone"></i>
                                <input type="tel" id="phone" name="phone" 
                                       value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <div class="input-group">
                                <i class="fas fa-map-marker-alt"></i>
                                <input type="text" id="address" name="address" 
                                       value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="terms-checkbox">
                            <input type="checkbox" name="terms" required>
                            <span class="checkmark"></span>
                            Tôi đồng ý với <a href="#" target="_blank">Điều khoản sử dụng</a> và <a href="#" target="_blank">Chính sách bảo mật</a>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-large">Đăng ký</button>
                </form>

                <div class="auth-footer">
                    <p>Đã có tài khoản? <a href="index.php?action=login">Đăng nhập ngay</a></p>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const button = input.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<?php include 'views/layout/footer.php'; ?>
