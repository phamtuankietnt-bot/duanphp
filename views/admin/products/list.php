<div class="content-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #2c3e50;">Danh sách sản phẩm</h2>
        <a href="index.php?action=admin&page=products&sub=add" class="btn btn-success">+ Thêm sản phẩm</a>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Hình ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Danh mục</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;"></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo number_format($product['price']); ?> đ</td>
                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($product['created_at'])); ?></td>
                        <td>
                            <a href="index.php?action=admin&page=products&sub=edit&id=<?php echo $product['id']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px; margin-right: 5px;">Sửa</a>
                            <a href="index.php?action=admin&page=products&sub=delete&id=<?php echo $product['id']; ?>" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #999;">Chưa có sản phẩm nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

