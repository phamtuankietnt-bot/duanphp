# Hướng dẫn cấu hình Google Gemini API Key

## Cách lấy API Key:

1. Truy cập: **https://makersuite.google.com/app/apikey**
2. Đăng nhập bằng tài khoản Google của bạn
3. Click vào nút **"Create API Key"** hoặc **"Get API Key"**
4. Copy API key được tạo (dạng: `AIzaSy...`)

## Cách thêm API Key:

### Cách 1: Sử dụng file `config/gemini_config.php` (Khuyến nghị)

1. Mở file: `config/gemini_config.php`
2. Tìm dòng:
   ```php
   $GEMINI_API_KEY = '';
   ```
3. Thêm API key của bạn vào giữa dấu nháy đơn:
   ```php
   $GEMINI_API_KEY = 'AIzaSyAbc123Def456Ghi789...';
   ```
4. Lưu file

### Cách 2: Sử dụng biến môi trường

Tạo file `.env` hoặc set biến môi trường:
```bash
export GEMINI_API_KEY='your_api_key_here'
```

### Ví dụ đúng:

```php
$GEMINI_API_KEY = 'AIzaSyAbc123Def456Ghi789Jkl012Mno345Pqr678';
```

### Lưu ý:

- ✅ Đảm bảo có dấu nháy đơn hoặc kép bao quanh API key
- ✅ Không có khoảng trắng thừa
- ✅ API key phải nằm trên một dòng
- ❌ Không commit API key lên Git (thêm vào `.gitignore`)
- ❌ Không chia sẻ API key với người khác

## Kiểm tra:

Sau khi thêm API key:
1. Làm mới trang admin
2. Vào **Bài viết** > **Thêm bài viết**
3. Thử tính năng **"Tạo bằng AI"**
4. Nếu vẫn lỗi, kiểm tra lại file `config/gemini_config.php`

## Xử lý lỗi:

Nếu vẫn gặp lỗi "API key chưa được cấu hình":
1. Kiểm tra file `config/gemini_config.php` có tồn tại không
2. Kiểm tra API key có đúng định dạng không (bắt đầu bằng `AIzaSy`)
3. Đảm bảo không có lỗi syntax PHP trong file config
4. Thử thêm API key trực tiếp vào file `config/gemini.php` (dòng 31) như fallback

