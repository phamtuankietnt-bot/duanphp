<?php 
$page_title = 'Đăng nhập - ShoeStore';
include 'views/layout/header.php'; 
?>

<main class="main">
    <div class="container">
        <div class="auth-page">
            <div class="auth-container">
                <div class="auth-header">
                    <h1>Đăng nhập</h1>
                    <p>Chào mừng bạn quay trở lại!</p>
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

                <form method="POST" action="index.php?action=login" class="auth-form">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="email" name="email" required 
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                    </div>

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

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            <span class="checkmark"></span>
                            Ghi nhớ đăng nhập
                        </label>
                        <a href="#" class="forgot-password">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-large">Đăng nhập</button>
                </form>

                <div class="auth-footer">
                    <p>Chưa có tài khoản? <a href="index.php?action=register">Đăng ký ngay</a></p>
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
