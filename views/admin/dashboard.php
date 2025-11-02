<div class="content-box">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">📈 Biểu đồ doanh thu hàng tháng</h2>
    <div style="position: relative; height: 400px; margin-bottom: 30px;">
        <canvas id="revenueChart"></canvas>
    </div>
</div>

<div class="content-box">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">Thống kê tổng quan</h2>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Tổng sản phẩm</h3>
            <div class="value"><?php echo number_format($stats['total_products']); ?></div>
            <small>Sản phẩm trong hệ thống</small>
        </div>
        
        <div class="stat-card">
            <h3>Tổng người dùng</h3>
            <div class="value"><?php echo number_format($stats['total_users']); ?></div>
            <small>Người dùng đã đăng ký</small>
        </div>
        
        <div class="stat-card">
            <h3>Tổng đơn hàng</h3>
            <div class="value"><?php echo number_format($stats['total_orders']); ?></div>
            <small>Đơn hàng đã đặt</small>
        </div>
        
        <div class="stat-card">
            <h3>Doanh thu</h3>
            <div class="value"><?php echo number_format($stats['total_revenue']); ?> đ</div>
            <small>Tổng doanh thu</small>
        </div>
        
        <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <h3>Đơn chờ xử lý</h3>
            <div class="value"><?php echo number_format($stats['pending_orders']); ?></div>
            <small>Đơn hàng đang xử lý</small>
        </div>
        
        <div class="stat-card" style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
            <h3>Tổng bài viết</h3>
            <div class="value"><?php echo number_format($stats['total_articles']); ?></div>
            <small>Bài viết đã xuất bản</small>
        </div>
    </div>
</div>

<div class="content-box">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">Sản phẩm bán chạy</h2>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Danh mục</th>
                <th>Số lượng bán</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($top_products) > 0): ?>
                <?php foreach ($top_products as $index => $product): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo number_format($product['price']); ?> đ</td>
                        <td><?php echo htmlspecialchars($product['category'] ?? 'N/A'); ?></td>
                        <td><?php echo number_format($product['total_sold'] ?? 0); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #999;">Chưa có sản phẩm nào được bán</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="content-box">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">Đơn hàng mới nhất</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Email</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Ngày đặt</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($recent_orders) > 0): ?>
                <?php foreach ($recent_orders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_email']); ?></td>
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
                            <a href="index.php?action=admin&page=orders&sub=detail&id=<?php echo $order['id']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">Chi tiết</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #999;">Chưa có đơn hàng nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthlyRevenueData = <?php echo json_encode($monthly_revenue ?? []); ?>;
    
    if (monthlyRevenueData.length === 0) {
        document.getElementById('revenueChart').parentElement.innerHTML = '<p style="text-align: center; color: #999; padding: 40px;">Chưa có dữ liệu doanh thu</p>';
        return;
    }
    
    const labels = monthlyRevenueData.map(item => {
        const [year, month] = item.month.split('-');
        const monthNames = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 
                           'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
        return monthNames[parseInt(month) - 1] + ' ' + year;
    });
    const data = monthlyRevenueData.map(item => parseFloat(item.revenue || 0));

    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: data,
                borderColor: 'rgb(52, 152, 219)',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgb(52, 152, 219)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        color: '#2c3e50'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('vi-VN', { 
                                    style: 'currency', 
                                    currency: 'VND' 
                                }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Doanh thu (VNĐ)',
                        font: {
                            size: 13,
                            weight: 'bold'
                        },
                        color: '#2c3e50'
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#7f8c8d',
                        callback: function(value, index, values) {
                            if (value >= 1000000) {
                                return (value / 1000000).toFixed(1) + 'M';
                            } else if (value >= 1000) {
                                return (value / 1000).toFixed(0) + 'K';
                            }
                            return value.toLocaleString('vi-VN');
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tháng',
                        font: {
                            size: 13,
                            weight: 'bold'
                        },
                        color: '#2c3e50'
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        color: '#7f8c8d',
                        maxRotation: 45,
                        minRotation: 0
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    });
});
</script>

