<div class="content-box">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">Thêm bài viết mới</h2>
    
    <div style="background: #e8f4f8; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #3498db;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="color: #2c3e50; margin: 0;">🤖 Tạo bài viết tự động bằng AI</h3>
            <a href="/duanmau/setup_gemini_key.php" target="_blank" style="background: #3498db; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 13px; font-weight: bold;">
                ⚙️ Thiết lập API Key
            </a>
        </div>
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 10px; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label>Chủ đề bài viết</label>
                <input type="text" id="ai_topic" placeholder="Ví dụ: Xu hướng giày thể thao 2024" style="width: 100%; padding: 10px;">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label>Danh mục</label>
                <select id="ai_category" style="width: 100%; padding: 10px;">
                    <option value="Tin tức">Tin tức</option>
                    <option value="Xu hướng">Xu hướng</option>
                    <option value="Review">Review</option>
                    <option value="Hướng dẫn">Hướng dẫn</option>
                    <option value="So sánh">So sánh</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label>Độ dài</label>
                <select id="ai_length" style="width: 100%; padding: 10px;">
                    <option value="short">Ngắn (300 từ)</option>
                    <option value="medium" selected>Vừa (600 từ)</option>
                    <option value="long">Dài (1000 từ)</option>
                </select>
            </div>
            <button type="button" id="generate_ai_btn" class="btn btn-primary" style="padding: 10px 20px;">
                ✨ Tạo bằng AI
            </button>
        </div>
        <div id="ai_loading" style="display: none; margin-top: 10px; color: #3498db;">
            <i class="fas fa-spinner fa-spin"></i> Đang tạo nội dung...
        </div>
        <div id="ai_error" style="display: none; margin-top: 10px; color: #e74c3c;"></div>
    </div>
    
    <form method="POST" action="" id="article_form">
        <div class="form-group">
            <label>Tiêu đề *</label>
            <input type="text" name="title" id="article_title" required>
        </div>
        
        <div class="form-group">
            <label>Tóm tắt</label>
            <textarea name="excerpt" id="article_excerpt"></textarea>
        </div>
        
        <div class="form-group">
            <label>Nội dung *</label>
            <textarea name="content" id="article_content" required></textarea>
        </div>
        
        <div class="form-group">
            <label>URL hình ảnh</label>
            <input type="text" name="image">
        </div>
        
        <div class="form-group">
            <label>Danh mục *</label>
            <select name="category" id="article_category" required>
                <option value="Tin tức">Tin tức</option>
                <option value="Xu hướng">Xu hướng</option>
                <option value="Review">Review</option>
                <option value="Hướng dẫn">Hướng dẫn</option>
                <option value="So sánh">So sánh</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Trạng thái *</label>
            <select name="status" required>
                <option value="draft">Bản nháp</option>
                <option value="published">Xuất bản</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Thêm bài viết</button>
        <a href="index.php?action=admin&page=articles&sub=list" class="btn" style="background: #95a5a6; color: white;">Hủy</a>
    </form>
</div>

<script>
document.getElementById('generate_ai_btn').addEventListener('click', function() {
    const topic = document.getElementById('ai_topic').value;
    const category = document.getElementById('ai_category').value;
    const length = document.getElementById('ai_length').value;
    
    if (!topic) {
        alert('Vui lòng nhập chủ đề bài viết!');
        return;
    }
    
    const loadingDiv = document.getElementById('ai_loading');
    const errorDiv = document.getElementById('ai_error');
    const btn = this;
    
    loadingDiv.style.display = 'block';
    errorDiv.style.display = 'none';
    btn.disabled = true;
    btn.textContent = '⏳ Đang xử lý...';
    
    fetch('index.php?action=admin&page=articles&sub=generateAI', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `topic=${encodeURIComponent(topic)}&category=${encodeURIComponent(category)}&length=${encodeURIComponent(length)}`
    })
    .then(response => response.json())
    .then(data => {
        loadingDiv.style.display = 'none';
        btn.disabled = false;
        btn.textContent = '✨ Tạo bằng AI';
        
        if (data.success) {
            document.getElementById('article_title').value = data.title;
            document.getElementById('article_excerpt').value = data.excerpt;
            document.getElementById('article_content').value = data.content;
            document.getElementById('article_category').value = category;
            
            alert('✓ Đã tạo nội dung thành công! Vui lòng kiểm tra và chỉnh sửa nếu cần.');
        } else {
            let errorMsg = data.message || 'Không thể tạo nội dung';
            // Kiểm tra nếu lỗi về API key
            if (errorMsg.includes('API key') || errorMsg.includes('400')) {
                errorMsg += '<br><br><a href="/duanmau/setup_gemini_key.php" target="_blank" style="color: #3498db; text-decoration: underline; font-weight: bold; background: #e8f4f8; padding: 10px; border-radius: 5px; display: inline-block;">👉 Click để thiết lập API Key</a>';
            }
            errorDiv.innerHTML = 'Lỗi: ' + errorMsg;
            errorDiv.style.display = 'block';
        }
    })
    .catch(error => {
        loadingDiv.style.display = 'none';
        btn.disabled = false;
        btn.textContent = '✨ Tạo bằng AI';
        errorDiv.textContent = 'Lỗi kết nối: ' + error.message;
        errorDiv.style.display = 'block';
    });
});
</script>

