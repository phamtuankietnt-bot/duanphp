<div class="content-box">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">Chỉnh sửa sản phẩm</h2>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Tên sản phẩm *</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="description"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Giá *</label>
            <input type="number" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
        </div>
        
        <div class="form-group">
            <label>URL hình ảnh *</label>
            <input type="text" name="image" value="<?php echo htmlspecialchars($product['image']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Danh mục *</label>
            <select name="category" required>
                <option value="Sneakers" <?php echo $product['category'] == 'Sneakers' ? 'selected' : ''; ?>>Sneakers</option>
                <option value="Boots" <?php echo $product['category'] == 'Boots' ? 'selected' : ''; ?>>Boots</option>
                <option value="Sandals" <?php echo $product['category'] == 'Sandals' ? 'selected' : ''; ?>>Sandals</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="index.php?action=admin&page=products&sub=list" class="btn" style="background: #95a5a6; color: white;">Hủy</a>
    </form>
</div>

