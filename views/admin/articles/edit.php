<div class="content-box">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">Chỉnh sửa bài viết</h2>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Tiêu đề *</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Tóm tắt</label>
            <textarea name="excerpt"><?php echo htmlspecialchars($article['excerpt'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Nội dung *</label>
            <textarea name="content" required><?php echo htmlspecialchars($article['content']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label>URL hình ảnh</label>
            <input type="text" name="image" value="<?php echo htmlspecialchars($article['image'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label>Danh mục *</label>
            <input type="text" name="category" value="<?php echo htmlspecialchars($article['category']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Trạng thái *</label>
            <select name="status" required>
                <option value="draft" <?php echo $article['status'] == 'draft' ? 'selected' : ''; ?>>Bản nháp</option>
                <option value="published" <?php echo $article['status'] == 'published' ? 'selected' : ''; ?>>Xuất bản</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="index.php?action=admin&page=articles&sub=list" class="btn" style="background: #95a5a6; color: white;">Hủy</a>
    </form>
</div>

