<div class="content-box">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">Thêm sản phẩm mới</h2>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Tên sản phẩm *</label>
            <input type="text" name="name" required>
        </div>
        
        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="description"></textarea>
        </div>
        
        <div class="form-group">
            <label>Giá *</label>
            <input type="number" name="price" step="0.01" required>
        </div>
        
        <div class="form-group">
            <label>URL hình ảnh *</label>
            <input type="text" name="image" required>
        </div>
        
        <div class="form-group">
            <label>Danh mục *</label>
            <select name="category" required>
                <option value="Sneakers">Sneakers</option>
                <option value="Boots">Boots</option>
                <option value="Sandals">Sandals</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
        <a href="index.php?action=admin&page=products&sub=list" class="btn" style="background: #95a5a6; color: white;">Hủy</a>
    </form>
</div>

