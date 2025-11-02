<div class="content-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #2c3e50;">Danh sách bài viết</h2>
        <a href="index.php?action=admin&page=articles&sub=add" class="btn btn-success">+ Thêm bài viết</a>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Tác giả</th>
                <th>Danh mục</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($articles) > 0): ?>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td><?php echo $article['id']; ?></td>
                        <td><?php echo htmlspecialchars($article['title']); ?></td>
                        <td><?php echo htmlspecialchars($article['author']); ?></td>
                        <td><?php echo htmlspecialchars($article['category']); ?></td>
                        <td>
                            <span style="padding: 5px 10px; background: <?php echo $article['status'] == 'published' ? '#27ae60' : '#f39c12'; ?>; color: white; border-radius: 5px; font-size: 12px;">
                                <?php echo $article['status'] == 'published' ? 'Đã xuất bản' : 'Bản nháp'; ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($article['created_at'])); ?></td>
                        <td>
                            <a href="index.php?action=admin&page=articles&sub=edit&id=<?php echo $article['id']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px; margin-right: 5px;">Sửa</a>
                            <a href="index.php?action=admin&page=articles&sub=delete&id=<?php echo $article['id']; ?>" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #999;">Chưa có bài viết nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

