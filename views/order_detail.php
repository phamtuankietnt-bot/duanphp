<?php include 'layout/header.php'; ?>

<div class="container">
    <div class="page-header">
        <div class="breadcrumb">
            <a href="index.php">Trang chủ</a>
            <span class="separator">></span>
            <a href="index.php?action=orders">Đơn hàng của tôi</a>
            <span class="separator">></span>
            <span>Chi tiết đơn hàng #<?php echo $order['id']; ?></span>
        </div>
        <h1>Chi tiết đơn hàng #<?php echo $order['id']; ?></h1>
        <p>Thông tin chi tiết về đơn hàng của bạn</p>
    </div>

    <div class="order-detail-content">
        <div class="order-detail-grid">
            <!-- Thông tin đơn hàng -->
            <div class="order-info-card">
                <div class="card-header">
                    <h3><i class="fas fa-info-circle"></i> Thông tin đơn hàng</h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="label">Mã đơn hàng:</span>
                        <span class="value">#<?php echo $order['id']; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Ngày đặt hàng:</span>
                        <span class="value"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Trạng thái:</span>
                        <span class="status-badge status-<?php echo $order['status']; ?>">
                            <i class="fas fa-<?php
                            switch($order['status']) {
                                case 'processing':
                                    echo 'cog';
                                    break;
                                case 'waiting_shipping':
                                    echo 'box';
                                    break;
                                case 'shipping':
                                    echo 'truck';
                                    break;
                                case 'completed':
                                    echo 'check-circle';
                                    break;
                                case 'cancelled':
                                    echo 'times-circle';
                                    break;
                                default:
                                    echo 'question-circle';
                            }
                            ?>"></i>
                            <?php
                            switch($order['status']) {
                                case 'processing':
                                    echo 'Đang xử lý';
                                    break;
                                case 'waiting_shipping':
                                    echo 'Chờ vận chuyển';
                                    break;
                                case 'shipping':
                                    echo 'Đang vận chuyển';
                                    break;
                                case 'completed':
                                    echo 'Đã hoàn thành';
                                    break;
                                case 'cancelled':
                                    echo 'Đã hủy';
                                    break;
                                default:
                                    echo ucfirst($order['status']);
                            }
                            ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="label">Tổng tiền:</span>
                        <span class="value total-amount"><?php echo number_format($order['total_amount']); ?> VNĐ</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Phương thức thanh toán:</span>
                        <span class="value"><?php echo htmlspecialchars($order['payment_method']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Thông tin giao hàng -->
            <div class="shipping-info-card">
                <div class="card-header">
                    <h3><i class="fas fa-truck"></i> Thông tin giao hàng</h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="label">Họ và tên:</span>
                        <span class="value"><?php echo htmlspecialchars($order['customer_name']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Email:</span>
                        <span class="value"><?php echo htmlspecialchars($order['customer_email']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Số điện thoại:</span>
                        <span class="value"><?php echo htmlspecialchars($order['customer_phone']); ?></span>
                    </div>
                    <div class="info-row address-row">
                        <span class="label">Địa chỉ giao hàng:</span>
                        <span class="value address-value">
                            <?php 
                            $address = trim($order['customer_address']);
                            if (empty($address) || strlen($address) < 5) {
                                echo '<span class="address-warning">Địa chỉ chưa đầy đủ</span>';
                            } else {
                                echo htmlspecialchars($address);
                            }
                            ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="order-items-card">
            <div class="card-header">
                <h3><i class="fas fa-shopping-bag"></i> Sản phẩm đã đặt</h3>
            </div>
            <div class="card-body">
                <div class="order-items-list">
                    <?php foreach ($order_items as $item): ?>
                        <div class="order-item">
                            <div class="item-image">
                                <img src="https://picsum.photos/80/80?random=<?php echo $item['product_id']; ?>" 
                                     alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                            </div>
                            <div class="item-details">
                                <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                                <p class="item-price"><?php echo number_format($item['product_price']); ?> VNĐ</p>
                                <p class="item-quantity">Số lượng: <?php echo $item['quantity']; ?></p>
                            </div>
                            <div class="item-total">
                                <span class="total-price"><?php echo number_format($item['total_price']); ?> VNĐ</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="order-summary">
                    <div class="summary-row">
                        <span>Tạm tính:</span>
                        <span><?php echo number_format($order['total_amount']); ?> VNĐ</span>
                    </div>
                    <div class="summary-row">
                        <span>Phí vận chuyển:</span>
                        <span>Miễn phí</span>
                    </div>
                    <div class="summary-row total">
                        <span><strong>Tổng cộng:</strong></span>
                        <span><strong><?php echo number_format($order['total_amount']); ?> VNĐ</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="order-actions">
            <a href="index.php?action=orders" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                Quay lại danh sách
            </a>
            <?php if ($order['status'] == 'processing'): ?>
                <button class="btn btn-danger" onclick="cancelOrder(<?php echo $order['id']; ?>)">
                    <i class="fas fa-times"></i>
                    Hủy đơn hàng
                </button>
            <?php endif; ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i>
                    In đơn hàng
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function cancelOrder(orderId) {
    if (confirm('Bạn có chắc muốn hủy đơn hàng này?')) {
        // Gửi request hủy đơn hàng
        fetch('index.php?action=cancelOrder', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'order_id=' + orderId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Đã hủy đơn hàng thành công!', 'success');
                setTimeout(() => {
                    window.location.href = 'index.php?action=orders';
                }, 1500);
            } else {
                showNotification(data.message || 'Có lỗi xảy ra khi hủy đơn hàng!', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Có lỗi xảy ra khi hủy đơn hàng!', 'error');
        });
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;

    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#27ae60' : '#e74c3c'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        animation: slideIn 0.3s ease;
    `;

    if (!document.getElementById('notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}
</script>

<?php include 'layout/footer.php'; ?>
