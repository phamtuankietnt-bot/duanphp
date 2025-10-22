<?php 
$page_title = 'Giỏ hàng - ShoeStore';
include 'views/layout/header.php'; 
?>

<main class="main">
    <div class="container">
        <div class="page-header">
            <h1>Giỏ hàng của bạn</h1>
            <div class="breadcrumb">
                <a href="index.php">Trang chủ</a> > <span>Giỏ hàng</span>
            </div>
        </div>

        <?php if (!empty($cart_items)): ?>
            <div class="cart-content">
                <div class="cart-items">
                    <div class="cart-header">
                        <h2>Sản phẩm trong giỏ hàng (<?php echo $cart_count; ?> sản phẩm)</h2>
                        <form method="POST" action="index.php?action=cart" style="display: inline;">
                            <input type="hidden" name="action" value="clear">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa tất cả sản phẩm?')">
                                Xóa tất cả
                            </button>
                        </form>
                    </div>

                    <div class="cart-controls">
                        <div class="select-all">
                            <label class="checkbox-label">
                                <input type="checkbox" id="select-all" onchange="toggleSelectAll()">
                                <span class="checkmark"></span>
                                Chọn tất cả
                            </label>
                        </div>
                        <div class="selected-actions">
                            <span id="selected-count">0 sản phẩm được chọn</span>
                        </div>
                    </div>

                    <div class="cart-list">
                        <?php 
                        $cart_items_array = array_values($cart_items);
                        $last_item_index = count($cart_items_array) - 1;
                        foreach($cart_items as $index => $item): ?>
                            <?php if (is_array($item) && isset($item['product_id'], $item['name'], $item['price'], $item['quantity'], $item['image'])): ?>
                            <div class="cart-item" data-product-id="<?php echo (int)$item['product_id']; ?>">
                                <div class="item-checkbox">
                                    <label class="checkbox-label">
                                        <input type="checkbox" class="item-checkbox-input" 
                                               value="<?php echo (int)$item['product_id']; ?>"
                                               <?php echo ($index == $last_item_index) ? 'checked' : ''; ?>>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                
                                <div class="item-image">
                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                </div>
                                
                                <div class="item-info">
                                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                    <p class="item-category"><?php echo isset($item['category']) ? htmlspecialchars($item['category']) : 'Sneakers'; ?></p>
                                    <p class="item-price"><?php echo number_format((float)$item['price']); ?> VNĐ</p>
                                </div>

                                <div class="item-quantity">
                                    <form method="POST" action="index.php?action=cart" class="quantity-form">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="product_id" value="<?php echo (int)$item['product_id']; ?>">
                                        <label>Số lượng:</label>
                                        <div class="quantity-controls">
                                            <button type="button" class="quantity-btn" onclick="changeQuantity(<?php echo (int)$item['product_id']; ?>, -1)">-</button>
                                            <input type="number" name="quantity" value="<?php echo (int)$item['quantity']; ?>" min="1" max="10" class="quantity-input" id="qty_<?php echo (int)$item['product_id']; ?>">
                                            <button type="button" class="quantity-btn" onclick="changeQuantity(<?php echo (int)$item['product_id']; ?>, 1)">+</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="item-total">
                                    <p class="total-price"><?php echo number_format((float)$item['price'] * (int)$item['quantity']); ?> VNĐ</p>
                                </div>

                                <div class="item-actions">
                                    <form method="POST" action="index.php?action=cart" style="display: inline;">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="product_id" value="<?php echo (int)$item['product_id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="cart-summary">
                    <div class="summary-card">
                        <h3>Tóm tắt đơn hàng</h3>
                        <div class="summary-details" id="summary-content">
                            <div class="no-selection">
                                <i class="fas fa-shopping-cart"></i>
                                <p>Chưa có sản phẩm nào được chọn</p>
                                <small>Vui lòng chọn sản phẩm để xem tóm tắt đơn hàng</small>
                            </div>
                        </div>
                        
                        <!-- Voucher Section -->
                        <div class="voucher-section" style="display: none;">
                            <h4>Mã giảm giá phù hợp</h4>
                            <div class="voucher-container">
                                <div class="voucher-scroll" id="voucherScroll">
                                    <?php if (!empty($vouchers)): ?>
                                        <?php foreach ($vouchers as $voucher): ?>
                                            <?php 
                                            // Tính toán mức giảm giá thực tế
                                            $actualDiscount = 0;
                                            if ($voucher['discount_type'] == 'percentage') {
                                                $actualDiscount = ($total_amount * $voucher['discount_value']) / 100;
                                                if ($voucher['max_discount_amount'] && $actualDiscount > $voucher['max_discount_amount']) {
                                                    $actualDiscount = $voucher['max_discount_amount'];
                                                }
                                            } else {
                                                $actualDiscount = $voucher['discount_value'];
                                            }
                                            ?>
                                            <div class="voucher-item" data-voucher-code="<?php echo htmlspecialchars($voucher['code']); ?>" 
                                                 data-min-amount="<?php echo $voucher['min_order_amount']; ?>"
                                                 data-discount-type="<?php echo $voucher['discount_type']; ?>"
                                                 data-discount-value="<?php echo $voucher['discount_value']; ?>"
                                                 data-max-discount="<?php echo $voucher['max_discount_amount']; ?>">
                                                <div class="voucher-header">
                                                    <span class="voucher-code"><?php echo htmlspecialchars($voucher['code']); ?></span>
                                                    <span class="voucher-discount">
                                                        <?php if ($voucher['discount_type'] == 'percentage'): ?>
                                                            -<?php echo $voucher['discount_value']; ?>%
                                                        <?php else: ?>
                                                            -<?php echo number_format($voucher['discount_value']); ?> VNĐ
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                                <div class="voucher-name"><?php echo htmlspecialchars($voucher['name']); ?></div>
                                                <div class="voucher-condition">
                                                    Đơn hàng từ <?php echo number_format($voucher['min_order_amount']); ?> VNĐ
                                                </div>
                                                <div class="voucher-savings">
                                                    <i class="fas fa-coins"></i>
                                                    Tiết kiệm: <strong><?php echo number_format($actualDiscount); ?> VNĐ</strong>
                                                </div>
                                                <button class="voucher-apply-btn" onclick="applyVoucher('<?php echo htmlspecialchars($voucher['code']); ?>')">
                                                    Áp dụng ngay
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="no-vouchers">
                                            <i class="fas fa-ticket-alt"></i>
                                            <span>Chưa có mã giảm giá phù hợp</span>
                                            <small>Thêm sản phẩm để mở khóa ưu đãi</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="voucher-scroll-indicator">
                                    <div class="voucher-scroll-progress" id="voucherScrollProgress"></div>
                                </div>
                            </div>
                        </div>
                        <div class="checkout-actions">
                            <a href="index.php?action=products" class="btn btn-secondary">Tiếp tục mua sắm</a>
                            <a href="index.php?action=checkout" class="btn btn-primary btn-large">Thanh toán</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <div class="empty-cart-content">
                    <i class="fas fa-shopping-cart"></i>
                    <h2>Giỏ hàng trống</h2>
                    <p>Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
                    <a href="index.php?action=products" class="btn btn-primary">Bắt đầu mua sắm</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
// Dữ liệu sản phẩm từ PHP
const cartData = <?php echo json_encode($cart_items); ?>;

function changeQuantity(productId, change) {
    const input = document.getElementById('qty_' + productId);
    let newValue = parseInt(input.value) + change;
    
    if (newValue < 1) newValue = 1;
    if (newValue > 10) newValue = 10;
    
    input.value = newValue;
    
    // Cập nhật số lượng giỏ hàng trong header
    updateCartCount();
    
    // Cập nhật tổng tiền nếu sản phẩm được chọn
    updateSelectedTotal();
    
    // Auto submit form when quantity changes
    input.form.submit();
}

// Cập nhật số lượng giỏ hàng trong header
function updateCartCount() {
    let cartCount = 0;
    const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(input => {
        cartCount += parseInt(input.value) || 0;
    });
    
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = cartCount;
        if (cartCount > 0) {
            cartCountElement.style.display = 'flex';
        } else {
            cartCountElement.style.display = 'none';
        }
    }
}

// Toggle chọn tất cả
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('select-all');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox-input');
    
    if (selectAllCheckbox.checked) {
        // Chọn tất cả
        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
    } else {
        // Bỏ chọn tất cả, nhưng giữ lại sản phẩm cuối cùng
        itemCheckboxes.forEach((checkbox, index) => {
            if (index === itemCheckboxes.length - 1) {
                checkbox.checked = true; // Giữ sản phẩm cuối cùng được chọn
            } else {
                checkbox.checked = false;
            }
        });
        // Hiển thị thông báo
        showNotification('Sản phẩm mới nhất vẫn được chọn để tạm tính', 'info');
    }
    
    // Cập nhật trạng thái checkbox "Chọn tất cả" sau khi thay đổi
    setTimeout(() => {
        updateSelectedCount();
    }, 10);
}

    // Cập nhật số lượng sản phẩm được chọn
    function updateSelectedCount() {
        const selectedCheckboxes = document.querySelectorAll('.item-checkbox-input:checked');
        const selectedCount = selectedCheckboxes.length;
        const totalItems = document.querySelectorAll('.item-checkbox-input').length;
        
        // Đảm bảo sản phẩm cuối cùng (mới nhất) luôn được chọn
        // CHỈ khi không phải đang trong quá trình toggle "Chọn tất cả"
        const allCheckboxes = document.querySelectorAll('.item-checkbox-input');
        if (allCheckboxes.length > 0) {
            const lastCheckbox = allCheckboxes[allCheckboxes.length - 1];
            const selectAllCheckbox = document.getElementById('select-all');
            
            // Chỉ force chọn sản phẩm cuối cùng khi không phải đang toggle select all
            if (!lastCheckbox.checked && !selectAllCheckbox.indeterminate) {
                lastCheckbox.checked = true;
                // Cập nhật lại sau khi check
                setTimeout(() => {
                    updateSelectedCount();
                }, 10);
                return;
            }
        }
        
        // Cập nhật text
        const selectedCountElement = document.getElementById('selected-count');
        if (selectedCountElement) {
            selectedCountElement.textContent = `${selectedCount} sản phẩm được chọn`;
        }
        
        // Cập nhật checkbox "Chọn tất cả"
        const selectAllCheckbox = document.getElementById('select-all');
        if (selectAllCheckbox) {
            // Chỉ chọn "Chọn tất cả" khi TẤT CẢ sản phẩm được chọn (không chỉ sản phẩm mới nhất)
            if (selectedCount === totalItems) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else if (selectedCount > 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
        }
        
        // Cập nhật tổng tiền
        updateSelectedTotal();
    }

// Cập nhật tổng tiền của sản phẩm đã chọn
function updateSelectedTotal() {
    const selectedCheckboxes = document.querySelectorAll('.item-checkbox-input:checked');
    const summaryContent = document.getElementById('summary-content');
    let selectedTotal = 0;
    
    selectedCheckboxes.forEach(checkbox => {
        const productId = parseInt(checkbox.value);
        const cartItem = document.querySelector(`[data-product-id="${productId}"]`);
        const quantityInput = cartItem.querySelector('.quantity-input');
        const quantity = parseInt(quantityInput.value) || 0;
        
        // Lấy giá từ DOM thay vì từ cartData
        const priceElement = cartItem.querySelector('.item-price');
        const price = parseFloat(priceElement.textContent.replace(/[^\d]/g, ''));
        
        selectedTotal += price * quantity;
    });
    
    if (selectedCheckboxes.length > 0) {
        // Hiển thị tóm tắt đơn hàng
        summaryContent.innerHTML = `
            <div class="summary-row">
                <span>Tạm tính:</span>
                <span id="subtotal-amount">${formatCurrency(selectedTotal)}</span>
            </div>
            <div class="summary-row">
                <span>Phí vận chuyển:</span>
                <span>Miễn phí</span>
            </div>
            <div class="summary-row total">
                <span><strong>Tổng cộng:</strong></span>
                <span><strong id="total-amount">${formatCurrency(selectedTotal)}</strong></span>
            </div>
        `;
        
        // Hiển thị voucher section
        const voucherSection = document.querySelector('.voucher-section');
        if (voucherSection) {
            voucherSection.style.display = 'block';
        }
        
        // Cập nhật voucher section để hiển thị với tổng tiền mới
        updateVoucherSection(selectedTotal);
    } else {
        // Hiển thị trạng thái chưa chọn
        summaryContent.innerHTML = `
            <div class="no-selection">
                <i class="fas fa-shopping-cart"></i>
                <p>Chưa có sản phẩm nào được chọn</p>
                <small>Vui lòng chọn sản phẩm để xem tóm tắt đơn hàng</small>
            </div>
        `;
        
        // Ẩn voucher section khi chưa chọn sản phẩm
        const voucherSection = document.querySelector('.voucher-section');
        if (voucherSection) {
            voucherSection.style.display = 'none';
        }
    }
}

// Cập nhật voucher section với tổng tiền mới
function updateVoucherSection(totalAmount) {
    // Gửi AJAX request để lấy voucher phù hợp với tổng tiền mới
    fetch('index.php?action=cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `action=get_vouchers&total_amount=${totalAmount}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.vouchers) {
            updateVoucherDisplay(data.vouchers, totalAmount);
        }
    })
    .catch(error => {
        console.error('Error updating vouchers:', error);
    });
}

// Cập nhật hiển thị voucher
function updateVoucherDisplay(vouchers, totalAmount) {
    const voucherScroll = document.querySelector('#voucherScroll');
    if (!voucherScroll) return;
    
    let voucherHTML = '';
    
    if (vouchers.length > 0) {
        vouchers.forEach(voucher => {
            // Tính toán mức giảm giá thực tế
            let actualDiscount = 0;
            if (voucher.discount_type === 'percentage') {
                actualDiscount = (totalAmount * voucher.discount_value) / 100;
                if (voucher.max_discount_amount && actualDiscount > voucher.max_discount_amount) {
                    actualDiscount = voucher.max_discount_amount;
                }
            } else {
                actualDiscount = voucher.discount_value;
            }
            
            voucherHTML += `
                <div class="voucher-item" data-voucher-code="${voucher.code}" 
                     data-min-amount="${voucher.min_order_amount}"
                     data-discount-type="${voucher.discount_type}"
                     data-discount-value="${voucher.discount_value}"
                     data-max-discount="${voucher.max_discount_amount}">
                    <div class="voucher-header">
                        <span class="voucher-code">${voucher.code}</span>
                        <span class="voucher-discount">
                            ${voucher.discount_type === 'percentage' ? '-' + voucher.discount_value + '%' : '-' + new Intl.NumberFormat('vi-VN').format(voucher.discount_value) + ' VNĐ'}
                        </span>
                    </div>
                    <div class="voucher-name">${voucher.name}</div>
                    <div class="voucher-condition">
                        Đơn hàng từ ${new Intl.NumberFormat('vi-VN').format(voucher.min_order_amount)} VNĐ
                    </div>
                    <div class="voucher-savings">
                        <i class="fas fa-coins"></i>
                        Tiết kiệm: <strong>${new Intl.NumberFormat('vi-VN').format(actualDiscount)} VNĐ</strong>
                    </div>
                    <button class="voucher-apply-btn" onclick="applyVoucher('${voucher.code}')">
                        Áp dụng ngay
                    </button>
                </div>
            `;
        });
    } else {
        voucherHTML = `
            <div class="no-vouchers">
                <i class="fas fa-ticket-alt"></i>
                <span>Chưa có mã giảm giá phù hợp</span>
                <small>Thêm sản phẩm để mở khóa ưu đãi</small>
            </div>
        `;
    }
    
    voucherScroll.innerHTML = voucherHTML;
}

// Format tiền tệ
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount) + ' VNĐ';
}


// Voucher functionality
function applyVoucher(voucherCode) {
        const voucherItem = document.querySelector(`[data-voucher-code="${voucherCode}"]`);
        const minAmount = parseFloat(voucherItem.dataset.minAmount);
        const discountType = voucherItem.dataset.discountType;
        const discountValue = parseFloat(voucherItem.dataset.discountValue);
        const maxDiscount = voucherItem.dataset.maxDiscount ? parseFloat(voucherItem.dataset.maxDiscount) : null;
        
        const currentTotal = parseFloat(document.getElementById('subtotal-amount').textContent.replace(/[^\d]/g, ''));
        
        // Check minimum order amount
        if (currentTotal < minAmount) {
            showNotification(`Đơn hàng tối thiểu ${minAmount.toLocaleString()} VNĐ để áp dụng voucher này`, 'error');
            return;
        }
        
        // Calculate discount
        let discount = 0;
        if (discountType === 'percentage') {
            discount = (currentTotal * discountValue) / 100;
            if (maxDiscount && discount > maxDiscount) {
                discount = maxDiscount;
            }
        } else {
            discount = discountValue;
        }
        
        // Apply discount
        const newTotal = currentTotal - discount;
        
        // Update UI
        document.getElementById('total-amount').textContent = newTotal.toLocaleString() + ' VNĐ';
        
        // Mark voucher as applied
        voucherItem.classList.add('applied');
        voucherItem.querySelector('.voucher-apply-btn').textContent = 'Hủy áp dụng';
        voucherItem.querySelector('.voucher-apply-btn').onclick = function() {
            removeVoucher(voucherCode);
        };
        
        // Show success message
        showNotification(`Đã áp dụng voucher ${voucherCode}! Tiết kiệm ${discount.toLocaleString()} VNĐ`, 'success');
        
        // Store applied voucher in session
        sessionStorage.setItem('appliedVoucher', JSON.stringify({
            code: voucherCode,
            discount: discount,
            discountType: discountType,
            discountValue: discountValue
        }));
    }

// Remove voucher function
function removeVoucher(voucherCode) {
    const voucherItem = document.querySelector(`[data-voucher-code="${voucherCode}"]`);
    
    // Get original total
    const currentTotal = parseFloat(document.getElementById('subtotal-amount').textContent.replace(/[^\d]/g, ''));
    
    // Reset total
    document.getElementById('total-amount').textContent = currentTotal.toLocaleString() + ' VNĐ';
    
    // Mark voucher as not applied
    voucherItem.classList.remove('applied');
    voucherItem.querySelector('.voucher-apply-btn').textContent = 'Áp dụng ngay';
    voucherItem.querySelector('.voucher-apply-btn').onclick = function() {
        applyVoucher(voucherCode);
    };
    
    // Show success message
    showNotification(`Đã hủy áp dụng voucher ${voucherCode}`, 'info');
    
    // Remove from session storage
    sessionStorage.removeItem('appliedVoucher');
}
    
// Show notification function
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Trigger animation
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    }, 3000);
}

// Cập nhật khi trang load
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
    updateSelectedCount();
    
    // Cập nhật tóm tắt ngay khi trang load (vì có sản phẩm được chọn mặc định)
    setTimeout(() => {
        updateSelectedTotal();
    }, 100);
    
    // Thêm event listener cho checkbox
    const itemCheckboxes = document.querySelectorAll('.item-checkbox-input');
    itemCheckboxes.forEach((checkbox, index) => {
        checkbox.addEventListener('change', function() {
            // Nếu bỏ chọn sản phẩm cuối cùng, tự động chọn lại
            const allCheckboxes = document.querySelectorAll('.item-checkbox-input');
            const isLastItem = index === allCheckboxes.length - 1;
            
            if (isLastItem && !this.checked) {
                // Hiển thị thông báo
                showNotification('Sản phẩm mới nhất sẽ luôn được chọn để tạm tính', 'info');
                // Tự động chọn lại
                setTimeout(() => {
                    this.checked = true;
                    updateSelectedCount();
                }, 100);
                return;
            }
            
            updateSelectedCount();
        });
    });
    
    // Thêm event listener cho select all checkbox
    const selectAllCheckbox = document.getElementById('select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', toggleSelectAll);
    }
    
    // Thêm event listener cho quantity input
    const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            updateCartCount();
            updateSelectedCount();
        });
    });
    
    // Load applied voucher on page load
    const appliedVoucher = sessionStorage.getItem('appliedVoucher');
    if (appliedVoucher) {
        const voucher = JSON.parse(appliedVoucher);
        const voucherItem = document.querySelector(`[data-voucher-code="${voucher.code}"]`);
        if (voucherItem) {
            voucherItem.classList.add('applied');
            voucherItem.querySelector('.voucher-apply-btn').textContent = 'Hủy áp dụng';
            voucherItem.querySelector('.voucher-apply-btn').onclick = function() {
                removeVoucher(voucher.code);
            };
            
            // Apply the discount to total
            const currentTotal = parseFloat(document.getElementById('subtotal-amount').textContent.replace(/[^\d]/g, ''));
            const newTotal = currentTotal - voucher.discount;
            document.getElementById('total-amount').textContent = newTotal.toLocaleString() + ' VNĐ';
        }
    }
    
    // Update scroll progress indicator
    const voucherScroll = document.querySelector('#voucherScroll');
    const voucherScrollProgress = document.querySelector('#voucherScrollProgress');
    
    if (voucherScroll && voucherScrollProgress) {
        function updateScrollProgress() {
            const scrollLeft = voucherScroll.scrollLeft;
            const scrollWidth = voucherScroll.scrollWidth;
            const clientWidth = voucherScroll.clientWidth;
            const maxScroll = scrollWidth - clientWidth;
            
            if (maxScroll > 0) {
                const progress = (scrollLeft / maxScroll) * 100;
                voucherScrollProgress.style.width = progress + '%';
            } else {
                voucherScrollProgress.style.width = '100%';
            }
        }
        
        voucherScroll.addEventListener('scroll', updateScrollProgress);
        
        // Initialize progress on load
        updateScrollProgress();
    }
});
</script>

<?php include 'views/layout/footer.php'; ?>
