<?php 
$page_title = 'Liên hệ - ShoeStore';
include 'views/layout/header.php'; 
?>

<main class="main">
    <div class="container">
        <div class="page-header">
            <h1>Liên hệ với chúng tôi</h1>
        </div>

        <div class="contact-content">
            <div class="contact-info">
                <h2>Thông tin liên hệ</h2>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h3>Địa chỉ</h3>
                        <p>123 Đường ABC, Quận 1, TP.HCM</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h3>Điện thoại</h3>
                        <p>0123 456 789</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3>Email</h3>
                        <p>info@shoestore.com</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h3>Giờ mở cửa</h3>
                        <p>8:00 - 22:00 (Thứ 2 - Chủ nhật)</p>
                    </div>
                </div>
            </div>

            <div class="contact-form">
                <h2>Gửi tin nhắn</h2>
                <?php if (isset($success) && $success): ?>
                    <div class="success-message">
                        <p>Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.</p>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Họ và tên *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Tin nhắn *</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi tin nhắn</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include 'views/layout/footer.php'; ?>
