<?php
/**
 * Script kiểm tra cấu hình Gemini API Key
 * Truy cập: http://localhost/duanmau/test_gemini_api.php
 */

require_once 'config/gemini.php';

echo "<h2>🔍 Kiểm tra cấu hình Gemini API Key</h2>";
echo "<hr>";

// Kiểm tra file config
$config_file = __DIR__ . '/config/gemini_config.php';
echo "<h3>1. Kiểm tra file config:</h3>";
if (file_exists($config_file)) {
    echo "✅ File <code>config/gemini_config.php</code> tồn tại<br>";
    echo "📄 Nội dung file:<br>";
    echo "<pre style='background: #f4f4f4; padding: 10px; border-left: 3px solid #3498db;'>";
    echo htmlspecialchars(file_get_contents($config_file));
    echo "</pre>";
} else {
    echo "❌ File <code>config/gemini_config.php</code> KHÔNG tồn tại!<br>";
    echo "👉 Hãy tạo file này và thêm API key vào.<br>";
}

echo "<hr>";

// Kiểm tra API key được load
echo "<h3>2. Kiểm tra API Key được load:</h3>";
$gemini = new GeminiAI();
$reflection = new ReflectionClass($gemini);
$property = $reflection->getProperty('api_key');
$property->setAccessible(true);
$loaded_key = $property->getValue($gemini);

if (!empty($loaded_key)) {
    $masked_key = substr($loaded_key, 0, 10) . '...' . substr($loaded_key, -5);
    echo "✅ API Key đã được load thành công!<br>";
    echo "🔑 API Key (đã ẩn): <code>$masked_key</code><br>";
    echo "📏 Độ dài: " . strlen($loaded_key) . " ký tự<br>";
} else {
    echo "❌ API Key CHƯA được load!<br>";
    echo "<strong>👉 Hãy làm theo các bước sau:</strong><br>";
    echo "<ol>";
    echo "<li>Mở file <code>config/gemini_config.php</code></li>";
    echo "<li>Tìm dòng: <code>\$GEMINI_API_KEY = '';</code></li>";
    echo "<li>Thay bằng: <code>\$GEMINI_API_KEY = 'YOUR_API_KEY_HERE';</code></li>";
    echo "<li>Lưu file và làm mới trang này</li>";
    echo "</ol>";
}

echo "<hr>";

// Kiểm tra biến môi trường
echo "<h3>3. Kiểm tra biến môi trường:</h3>";
$env_key = getenv('GEMINI_API_KEY');
if ($env_key) {
    echo "✅ Tìm thấy GEMINI_API_KEY trong biến môi trường<br>";
} else {
    echo "ℹ️ Không tìm thấy GEMINI_API_KEY trong biến môi trường<br>";
}

echo "<hr>";

// Test API key
if (!empty($loaded_key)) {
    echo "<h3>4. Test API Key với Gemini API:</h3>";
    echo "Đang kiểm tra API key...<br>";
    
    $test_data = [
        'contents' => [
            [
                'parts' => [
                    [
                        'text' => 'Say hello'
                    ]
                ]
            ]
        ]
    ];
    
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . urlencode($loaded_key);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($test_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $test_response = curl_exec($ch);
    $test_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($test_http_code == 200) {
        echo "✅ API Key hợp lệ! Kết nối thành công.<br>";
    } else {
        $test_error = json_decode($test_response, true);
        echo "❌ API Key không hợp lệ!<br>";
        echo "<strong>Lỗi:</strong> ";
        if (isset($test_error['error']['message'])) {
            echo htmlspecialchars($test_error['error']['message']);
        } else {
            echo "HTTP Code: $test_http_code";
        }
        echo "<br><br>";
        echo "<div style='background: #fee; padding: 15px; border-left: 4px solid #e74c3c; margin: 10px 0;'>";
        echo "<strong>👉 Giải pháp:</strong><br>";
        echo "1. Kiểm tra lại API key tại <a href='https://makersuite.google.com/app/apikey' target='_blank'>Google AI Studio</a><br>";
        echo "2. Tạo API key mới nếu cần<br>";
        echo "3. Copy API key chính xác (không có khoảng trắng thừa)<br>";
        echo "4. Dán vào file config/gemini_config.php<br>";
        echo "</div>";
    }
}

echo "<hr>";

// Hướng dẫn
echo "<h3>📖 Hướng dẫn thêm API Key:</h3>";
echo "<div style='background: #e8f4f8; padding: 15px; border-left: 4px solid #3498db;'>";
echo "<strong>Bước 1:</strong> Lấy API Key tại <a href='https://makersuite.google.com/app/apikey' target='_blank'>https://makersuite.google.com/app/apikey</a><br><br>";
echo "<strong>Bước 2:</strong> Mở file <code>config/gemini_config.php</code><br><br>";
echo "<strong>Bước 3:</strong> Sửa dòng này:<br>";
echo "<pre style='background: white; padding: 10px; margin: 10px 0;'>\$GEMINI_API_KEY = '';</pre>";
echo "Thành:<br>";
echo "<pre style='background: white; padding: 10px; margin: 10px 0;'>\$GEMINI_API_KEY = 'AIzaSy...API_KEY_CỦA_BẠN...';</pre>";
echo "<strong>Bước 4:</strong> Lưu file và làm mới trang này để kiểm tra lại<br>";
echo "</div>";

echo "<hr>";
echo "<p><small>Script này chỉ để kiểm tra. Xóa file này sau khi đã cấu hình xong để bảo mật.</small></p>";
?>

