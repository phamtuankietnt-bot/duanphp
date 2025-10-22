<?php include 'layout/header.php'; ?>

<div class="container">
    <div class="page-header">
        <div class="breadcrumb">
            <a href="index.php">Trang chủ</a>
            <span class="separator">></span>
            <span>Đơn hàng của tôi</span>
        </div>
        <h1>Đơn hàng của tôi</h1>
        <p>Quản lý và theo dõi các đơn hàng của bạn</p>
    </div>

    <!-- Filter Tabs -->
    <div class="order-filters">
        <div class="filter-tabs">
            <button class="filter-tab active" data-status="all">
                <i class="fas fa-list"></i>
                <span>Tất cả</span>
                <span class="count" id="count-all">0</span>
            </button>
            <button class="filter-tab" data-status="processing">
                <i class="fas fa-cog"></i>
                <span>Đang xử lý</span>
                <span class="count" id="count-processing">0</span>
            </button>
            <button class="filter-tab" data-status="waiting_shipping">
                <i class="fas fa-box"></i>
                <span>Chờ vận chuyển</span>
                <span class="count" id="count-waiting_shipping">0</span>
            </button>
            <button class="filter-tab" data-status="shipping">
                <i class="fas fa-truck"></i>
                <span>Đang vận chuyển</span>
                <span class="count" id="count-shipping">0</span>
            </button>
            <button class="filter-tab" data-status="completed">
                <i class="fas fa-check-circle"></i>
                <span>Đã hoàn thành</span>
                <span class="count" id="count-completed">0</span>
            </button>
            <button class="filter-tab" data-status="cancelled">
                <i class="fas fa-times-circle"></i>
                <span>Đã hủy</span>
                <span class="count" id="count-cancelled">0</span>
            </button>
        </div>
    </div>

    <div class="orders-content">
        <?php if (empty($orders)): ?>
            <div class="empty-orders">
                <div class="empty-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <h3>Chưa có đơn hàng nào</h3>
                <p>Bạn chưa có đơn hàng nào. Hãy bắt đầu mua sắm để tạo đơn hàng đầu tiên!</p>
                <div class="empty-actions">
                    <a href="index.php?action=products" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i>
                        Mua sắm ngay
                    </a>
                    <a href="index.php" class="btn btn-outline">
                        <i class="fas fa-home"></i>
                        Về trang chủ
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="orders-list">
                <?php foreach ($orders as $order): ?>
                    <div class="order-card" data-status="<?php echo $order['status']; ?>">
                        <div class="order-header">
                            <div class="order-info">
                                <h3>Đơn hàng #<?php echo $order['id']; ?></h3>
                                <p class="order-date">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                </p>
                            </div>
                            <div class="order-status">
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
                        </div>

                        <div class="order-details">
                            <div class="order-summary">
                                <div class="summary-row">
                                    <span>Tổng tiền:</span>
                                    <span class="total-amount"><?php echo number_format($order['total_amount']); ?> VNĐ</span>
                                </div>
                                <div class="summary-row">
                                    <span>Phương thức thanh toán:</span>
                                    <span><?php echo htmlspecialchars($order['payment_method']); ?></span>
                                </div>
                                <div class="summary-row">
                                    <span>Địa chỉ giao hàng:</span>
                                    <span><?php echo htmlspecialchars($order['customer_address']); ?></span>
                                </div>
                            </div>

                            <div class="order-actions">
                                <a href="index.php?action=orderDetail&id=<?php echo $order['id']; ?>" 
                                   class="btn-outline">
                                    <i class="fas fa-eye"></i>
                                    Xem chi tiết
                                </a>
                                <?php if ($order['status'] == 'pending'): ?>
                                    <button class="btn btn-danger" onclick="cancelOrder(<?php echo $order['id']; ?>)">
                                        <i class="fas fa-times"></i>
                                        Hủy đơn hàng
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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
                location.reload();
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

// Order Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const orderCards = document.querySelectorAll('.order-card');
    const countElements = {
        'all': document.getElementById('count-all'),
        'processing': document.getElementById('count-processing'),
        'waiting_shipping': document.getElementById('count-waiting_shipping'),
        'shipping': document.getElementById('count-shipping'),
        'completed': document.getElementById('count-completed'),
        'cancelled': document.getElementById('count-cancelled')
    };

    // Count orders by status
    function countOrders() {
        const counts = {
            'all': orderCards.length,
            'processing': 0,
            'waiting_shipping': 0,
            'shipping': 0,
            'completed': 0,
            'cancelled': 0
        };

        orderCards.forEach(card => {
            const status = card.getAttribute('data-status');
            if (counts.hasOwnProperty(status)) {
                counts[status]++;
            }
        });

        // Update count displays
        Object.keys(counts).forEach(status => {
            if (countElements[status]) {
                countElements[status].textContent = counts[status];
            }
        });
    }

    // Filter orders by status
    function filterOrders(status) {
        orderCards.forEach(card => {
            if (status === 'all' || card.getAttribute('data-status') === status) {
                card.style.display = 'block';
                card.style.animation = 'fadeIn 0.3s ease-in-out';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Add click event listeners to filter tabs
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            filterTabs.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Get status from data attribute
            const status = this.getAttribute('data-status');
            
            // Filter orders
            filterOrders(status);
        });
    });

    // Initialize counts
    countOrders();
});
</script>

<?php include 'layout/footer.php'; ?>
