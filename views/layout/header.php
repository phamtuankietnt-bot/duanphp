<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Cửa hàng giày'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1><a href="index.php">ShoeStore</a></h1>
                    <span class="tagline">Premium Footwear</span>
                </div>
                <nav class="nav">
                    <ul>
                        <li><a href="index.php">Trang chủ</a></li>
                        <li><a href="index.php?action=products">Sản phẩm</a></li>
                        <li><a href="index.php?action=articles">Bài viết</a></li>
                        <li><a href="index.php?action=about">Giới thiệu</a></li>
                        <li><a href="index.php?action=contact">Liên hệ</a></li>
                        <li><a href="index.php?action=orders" class="orders-nav">
                            <i class="fas fa-receipt"></i>
                            <span>Đơn hàng</span>
                        </a></li>
                        <li><a href="index.php?action=cart" class="cart-nav">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count" id="cart-count"><?php echo $cart_count; ?></span>
                        </a></li>
                    </ul>
                </nav>
                <div class="user-section">
                    <?php
                    require_once 'config/session.php';
                    startSession();
                    
                    // Lấy số lượng sản phẩm trong giỏ hàng
                    $cart_count = getCartCount();
                    
                    if (isset($_SESSION['user_id'])): 
                    ?>
                        <div class="user-menu">
                            <a href="index.php?action=profile" class="user-icon">
                                <i class="fas fa-user"></i>
                                <span class="user-name"><?php echo htmlspecialchars(isset($_SESSION['user_full_name']) ? $_SESSION['user_full_name'] : (isset($_SESSION['username']) ? $_SESSION['username'] : 'User')); ?></span>
                            </a>
                            <div class="user-dropdown">
                                <a href="index.php?action=profile"><i class="fas fa-user"></i> Tài khoản</a>
                                <a href="index.php?action=logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="index.php?action=login" class="login-icon">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Đăng nhập</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <script>
    // Cập nhật số lượng giỏ hàng
    function updateCartCount() {
        // Lấy số lượng từ PHP session (đã được tính toán sẵn)
        const cartCountElement = document.getElementById('cart-count');
        if (cartCountElement) {
            const currentCount = parseInt(cartCountElement.textContent) || 0;
            
            // Ẩn/hiện badge dựa trên số lượng
            if (currentCount > 0) {
                cartCountElement.style.display = 'flex';
            } else {
                cartCountElement.style.display = 'none';
            }
        }
    }

    // Cập nhật số lượng giỏ hàng từ server
    function updateCartCountFromServer() {
        fetch('index.php?action=cart', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const serverCount = data.cart_count || 0;
            const currentElement = document.getElementById('cart-count');
            if (currentElement) {
                currentElement.textContent = serverCount;
                if (serverCount > 0) {
                    currentElement.style.display = 'flex';
                } else {
                    currentElement.style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error updating cart count:', error);
        });
    }

    // Cập nhật khi trang load
    document.addEventListener('DOMContentLoaded', function() {
        updateCartCount();
    });

    // Lắng nghe sự kiện cập nhật giỏ hàng
    window.addEventListener('storage', function(e) {
        if (e.key === 'cart') {
            updateCartCount();
        }
    });

    // Hàm để thêm sản phẩm vào giỏ hàng (có thể gọi từ các trang khác)
    function addToCart(productId, quantity = 1) {
        console.log('Adding to cart:', {productId, quantity});
        
        // Kiểm tra tham số
        if (!productId || productId <= 0) {
            showNotification('ID sản phẩm không hợp lệ!', 'error');
            return;
        }
        
        if (!quantity || quantity <= 0) {
            showNotification('Số lượng không hợp lệ!', 'error');
            return;
        }
        
        // Gửi request đến server để cập nhật session
        fetch('index.php?action=cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `action=add&product_id=${productId}&quantity=${quantity}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Hiển thị thông báo thành công
                showNotification('Đã thêm sản phẩm vào giỏ hàng!', 'success');
                
                // Cập nhật số lượng giỏ hàng từ server
                updateCartCountFromServer();
            } else {
                showNotification(data.message || 'Có lỗi xảy ra khi thêm sản phẩm!', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Có lỗi xảy ra khi thêm sản phẩm!', 'error');
        });
    }

    // Hàm hiển thị thông báo
    function showNotification(message, type = 'success') {
        // Tạo element thông báo
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        // Thêm CSS cho thông báo
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
        
        // Thêm animation CSS
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
        
        // Thêm vào body
        document.body.appendChild(notification);
        
        // Tự động ẩn sau 3 giây
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
