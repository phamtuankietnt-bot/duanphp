<div class="content-box">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div style="background: #27ae60; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <strong>✓ Thành công!</strong> Đã cập nhật trạng thái đơn hàng.
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
        <div style="background: #e74c3c; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <strong>✓ Đã hủy!</strong> Đã hủy đơn hàng thành công.
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
        <div style="background: #e74c3c; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <strong>✗ Lỗi!</strong> Không thể cập nhật trạng thái đơn hàng.
        </div>
    <?php endif; ?>
    
    <h2 style="margin-bottom: 20px; color: #2c3e50;">Chi tiết đơn hàng #<?php echo $order['id']; ?></h2>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
        <div>
            <h3 style="margin-bottom: 15px; color: #2c3e50;">Thông tin khách hàng</h3>
            <p><strong>Tên:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
            <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
            <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['customer_address']); ?></p>
        </div>
        
        <div>
            <h3 style="margin-bottom: 15px; color: #2c3e50;">Thông tin đơn hàng</h3>
            <p><strong>Tổng tiền:</strong> <span style="color: #e74c3c; font-size: 20px; font-weight: bold;"><?php echo number_format($order['total_amount']); ?> đ</span></p>
            <p><strong>Phương thức thanh toán:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
            <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
            
            <form method="POST" action="index.php?action=admin&page=orders&sub=updateStatus&id=<?php echo $order['id']; ?>" style="margin-top: 15px;">
                <div class="form-group">
                    <label><strong>Trạng thái đơn hàng:</strong></label>
                    <select name="status" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px; font-size: 14px;">
                        <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>⏳ Đang xử lý</option>
                        <option value="waiting_shipping" <?php echo $order['status'] == 'waiting_shipping' ? 'selected' : ''; ?>>📦 Chờ vận chuyển</option>
                        <option value="shipping" <?php echo $order['status'] == 'shipping' ? 'selected' : ''; ?>>🚚 Đang giao</option>
                        <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>✅ Hoàn thành</option>
                        <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>❌ Đã hủy</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">💾 Cập nhật trạng thái</button>
            </form>
            
            <?php if ($order['status'] != 'cancelled' && $order['status'] != 'completed'): ?>
                <div style="margin-top: 15px;">
                    <a href="index.php?action=admin&page=orders&sub=cancel&id=<?php echo $order['id']; ?>" 
                       class="btn btn-danger" 
                       onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');"
                       style="margin-top: 10px;">
                        ❌ Hủy đơn hàng
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <h3 style="margin-bottom: 15px; color: #2c3e50;">Sản phẩm trong đơn</h3>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($order_items) > 0): ?>
                <?php foreach ($order_items as $index => $item): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo number_format($item['product_price']); ?> đ</td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo number_format($item['total_price']); ?> đ</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #999;">Không có sản phẩm</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        <a href="index.php?action=admin&page=orders&sub=list" class="btn" style="background: #95a5a6; color: white;">Quay lại</a>
    </div>
</div>

