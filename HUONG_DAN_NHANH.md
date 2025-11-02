# 🚀 Hướng dẫn nhanh - Kích hoạt Gemini API

## Các bước thực hiện:

### 1️⃣ Mở Google Cloud Console
👉 **https://console.cloud.google.com/**

### 2️⃣ Chọn Project đúng
- Click dropdown project ở thanh trên cùng
- Chọn project của API key bạn đã tạo
- (Hoặc tạo project mới nếu chưa có)

### 3️⃣ Kích hoạt API
👉 **https://console.cloud.google.com/apis/library**

- Tìm: **"Generative Language API"**
- Click vào API đó
- Click **"Enable"** (Kích hoạt)
- Đợi 2-3 phút

### 4️⃣ Kiểm tra
- Vào **"APIs & Services"** → **"Enabled APIs"**
- Tìm **"Generative Language API"** trong danh sách
- Nếu có → ✅ Đã kích hoạt thành công!

### 5️⃣ Test lại
- Quay lại: **http://localhost/duanmau/test_my_api_key.php**
- Thử lại tính năng AI trong admin panel

---

## ⚠️ Nếu vẫn lỗi:

**Vấn đề:** API key không thuộc project đã kích hoạt API

**Giải pháp:**
1. Tạo API key mới từ project đã kích hoạt API
2. Copy API key mới
3. Cập nhật vào form thiết lập
4. Test lại

---

## 💡 Tips:

- Đảm bảo đăng nhập cùng tài khoản Google đã dùng để tạo API key
- Đợi 5-10 phút sau khi kích hoạt API để hệ thống xử lý
- Một số models yêu cầu billing, nhưng có tier miễn phí

