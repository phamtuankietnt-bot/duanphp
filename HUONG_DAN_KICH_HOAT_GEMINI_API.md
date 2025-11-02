# Hướng dẫn kích hoạt quyền truy cập Gemini API

## Bước 1: Truy cập Google Cloud Console

1. Truy cập: **https://console.cloud.google.com/**
2. Đăng nhập bằng tài khoản Google **CÙNG TÀI KHOẢN** đã dùng để tạo API key tại Google AI Studio

## Bước 2: Chọn hoặc tạo Project

### Nếu đã có project:
1. Ở thanh trên cùng, click vào dropdown project (hiển thị tên project hiện tại)
2. Chọn project tương ứng với API key bạn đã tạo (thường là project "cửa hàng giày" hoặc tên project bạn đã tạo)

### Nếu chưa có project:
1. Click vào dropdown project
2. Click **"New Project"** hoặc **"Tạo dự án"**
3. Đặt tên project (ví dụ: "Gemini API Project")
4. Click **"Create"** hoặc **"Tạo"**
5. Chờ vài giây để project được tạo

## Bước 3: Kích hoạt Generative Language API

1. Truy cập: **https://console.cloud.google.com/apis/library**
   - Hoặc trong Google Cloud Console, vào menu ☰ → **"APIs & Services"** → **"Library"**

2. Trong ô tìm kiếm, gõ: **"Generative Language API"** hoặc **"Gemini API"**

3. Click vào **"Generative Language API"** (hoặc "Gemini API" nếu có)

4. Click nút **"Enable"** hoặc **"Kích hoạt"**

5. Đợi vài phút để API được kích hoạt (thường 1-2 phút)

## Bước 4: Kiểm tra API đã được kích hoạt

1. Vào **"APIs & Services"** → **"Enabled APIs"** hoặc **"APIs đã bật"**
2. Kiểm tra xem **"Generative Language API"** có trong danh sách không
3. Nếu có → API đã được kích hoạt thành công ✅

## Bước 5: Kiểm tra API Key

1. Truy cập: **https://aistudio.google.com/app/api-keys**
2. Đảm bảo API key của bạn thuộc về project đã kích hoạt API
3. Nếu API key thuộc project khác:
   - Tạo API key mới từ project đã kích hoạt API
   - Hoặc kích hoạt API trong project của API key hiện tại

## Bước 6: Test lại

1. Quay lại trang test: **http://localhost/duanmau/test_my_api_key.php**
2. Hoặc thử lại tính năng tạo bài viết bằng AI trong admin panel

## Lưu ý quan trọng:

### Về Billing (Thanh toán):
- Một số models (như gemini-1.5-pro, gemini-1.5-flash) có thể yêu cầu **"Thiết lập thanh toán"**
- Tuy nhiên, Google cung cấp **miễn phí** cho một số lượng requests nhất định mỗi tháng
- Bạn có thể thiết lập billing để sử dụng nhiều hơn, nhưng vẫn có tier miễn phí

### Nếu vẫn không hoạt động:

1. **Kiểm tra project đúng chưa:**
   - API key phải thuộc project đã kích hoạt Generative Language API
   - Nếu không đúng, tạo API key mới từ project đã kích hoạt API

2. **Đợi thời gian kích hoạt:**
   - API có thể cần 5-10 phút để hoàn toàn kích hoạt
   - Thử lại sau vài phút

3. **Kiểm tra billing:**
   - Vào "APIs & Services" → "Enabled APIs"
   - Xem Generative Language API có yêu cầu billing không
   - Nếu có, click "Manage" và thiết lập billing (có thể miễn phí trong giới hạn)

4. **Tạo API key mới:**
   - Nếu API key cũ không hoạt động, tạo mới từ project đã kích hoạt API
   - Copy API key mới và cập nhật vào config

## Video hướng dẫn (tham khảo):

- Tìm trên YouTube: "How to enable Generative Language API Google Cloud"
- Tìm trên YouTube: "Enable Gemini API Google Cloud Console"

