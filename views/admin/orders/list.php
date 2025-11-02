<div class="content-box">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">Danh sách đơn hàng</h2>
    
    <table class="table">
        <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Ngày đặt</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($orders) > 0): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_email']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_phone']); ?></td>
                        <td><?php echo number_format($order['total_amount']); ?> đ</td>
                        <td>
                            <?php
                            $status_labels = [
                                'processing' => 'Đang xử lý',
                                'waiting_shipping' => 'Chờ vận chuyển',
                                'shipping' => 'Đang giao',
                                'completed' => 'Hoàn thành',
                                'cancelled' => 'Đã hủy'
                            ];
                            $status_colors = [
                                'processing' => '#f39c12',
                                'waiting_shipping' => '#3498db',
                                'shipping' => '#9b59b6',
                                'completed' => '#27ae60',
                                'cancelled' => '#e74c3c'
                            ];
                            $status = isset($order['status']) && !empty($order['status']) ? $order['status'] : 'processing';
                            $status_label = isset($status_labels[$status]) ? $status_labels[$status] : 'Không xác định';
                            $status_color = isset($status_colors[$status]) ? $status_colors[$status] : '#95a5a6';
                            ?>
                            <span style="padding: 5px 10px; background: <?php echo $status_color; ?>; color: white; border-radius: 5px; font-size: 12px;">
                                <?php echo $status_label; ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                        <td>
                            <a href="index.php?action=admin&page=orders&sub=detail&id=<?php echo $order['id']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px; margin-right: 5px;">📋 Chi tiết</a>
                            <?php if ($status != 'completed' && $status != 'cancelled'): ?>
                                <a href="index.php?action=admin&page=orders&sub=detail&id=<?php echo $order['id']; ?>" class="btn btn-success" style="padding: 5px 10px; font-size: 12px; margin-right: 5px;">⚡ Xử lý</a>
                                <a href="index.php?action=admin&page=orders&sub=cancel&id=<?php echo $order['id']; ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng #<?php echo $order['id']; ?>?');"
                                   style="padding: 5px 10px; font-size: 12px;">❌ Hủy</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: #999;">Chưa có đơn hàng nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

