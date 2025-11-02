<?php 
$page_title = 'Đặt hàng thành công - ShoeStore';
include 'views/layout/header.php'; 
?>

<main class="main">
    <div class="container">
        <div class="success-page">
            <?php if ($order): ?>
                <div class="success-content">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h1>Đặt hàng thành công!</h1>
                    <p class="success-message">
                        Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất.
                    </p>
                    
                    <div class="order-info">
                        <h2>Thông tin đơn hàng</h2>
                        <div class="order-details">
                            <div class="detail-row">
                                <span class="label">Mã đơn hàng:</span>
                                <span class="value">#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Ngày đặt:</span>
                                <span class="value"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Tổng tiền:</span>
                                <span class="value"><?php echo number_format($order['total_amount']); ?> VNĐ</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Trạng thái:</span>
                                <span class="value status-pending">Đang xử lý</span>
                            </div>
                        </div>
                    </div>

                    <div class="order-items-summary">
                        <h3>Sản phẩm đã đặt</h3>
                        <div class="items-list">
                            <?php foreach($order_items as $item): ?>
                            <div class="order-item">
                                <div class="item-image">
                                    <div class="placeholder-image">
                                        <i class="fas fa-shoe-prints"></i>
                                    </div>
                                </div>
                                <div class="item-info">
                                    <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                                    <p>Số lượng: <?php echo $item['quantity']; ?></p>
                                    <p>Giá: <?php echo number_format($item['product_price']); ?> VNĐ</p>
                                </div>
                                <div class="item-total">
                                    <?php echo number_format($item['total_price']); ?> VNĐ
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="next-steps">
                        <h3>Bước tiếp theo</h3>
                        <div class="steps">
                            <div class="step">
                                <i class="fas fa-envelope"></i>
                                <p>Chúng tôi sẽ gửi email xác nhận đến địa chỉ email của bạn</p>
                            </div>
                            <div class="step">
                                <i class="fas fa-phone"></i>
                                <p>Nhân viên sẽ liên hệ để xác nhận đơn hàng</p>
                            </div>
                            <div class="step">
                                <i class="fas fa-truck"></i>
                                <p>Đơn hàng sẽ được giao trong 2-5 ngày làm việc</p>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <a href="index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
                        <a href="index.php?action=contact" class="btn btn-secondary">Liên hệ hỗ trợ</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="error-content">
                    <div class="error-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h1>Không tìm thấy đơn hàng</h1>
                    <p>Đơn hàng không tồn tại hoặc đã bị xóa.</p>
                    <a href="index.php" class="btn btn-primary">Về trang chủ</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'views/layout/footer.php'; ?>
