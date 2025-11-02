<?php
/**
 * Script test API key cụ thể
 */

$api_key = 'AIzaSyC2I1o9SRW9EvtRRDG6atUWGw8ITKyo5so';

echo "<h2>🔍 Kiểm tra API Key của bạn</h2>";
echo "<hr>";

// Kiểm tra format
echo "<h3>1. Kiểm tra format API Key:</h3>";
if (preg_match('/^AIzaSy[a-zA-Z0-9_-]{30,40}$/', $api_key)) {
    echo "✅ Format đúng!<br>";
    echo "Độ dài: " . strlen($api_key) . " ký tự<br>";
    echo "Bắt đầu bằng: " . substr($api_key, 0, 6) . "<br>";
} else {
    echo "❌ Format không đúng!<br>";
}

echo "<hr>";

// Test với các models
$models = [
    'gemini-pro',
    'gemini-1.5-pro', 
    'gemini-1.5-flash'
];

$test_data = [
    'contents' => [
        [
            'parts' => [
                [
                    'text' => 'Say hello in Vietnamese'
                ]
            ]
        ]
    ]
];

echo "<h3>2. Testing API Key với các models:</h3>";

$working_model = null;

foreach ($models as $model) {
    echo "<div style='margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px;'>";
    echo "<h4>🔍 Testing: <strong>$model</strong></h4>";
    
    $url = "https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent?key=" . urlencode($api_key);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($test_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);
    
    echo "<p><strong>HTTP Code:</strong> <span style='font-size: 18px; font-weight: bold;'>$http_code</span></p>";
    
    if ($http_code == 200) {
        echo "<p style='color: green; font-size: 16px;'><strong>✅ THÀNH CÔNG! Model $model hoạt động!</strong></p>";
        $result = json_decode($response, true);
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            echo "<p><strong>Response:</strong> " . htmlspecialchars($result['candidates'][0]['content']['parts'][0]['text']) . "</p>";
        }
        $working_model = $model;
        break; // Dừng khi tìm thấy model hoạt động
    } else {
        echo "<p style='color: red;'><strong>❌ LỖI</strong></p>";
        if ($response) {
            $error = json_decode($response, true);
            echo "<div style='background: #fee; padding: 10px; border-radius: 5px; margin-top: 10px;'>";
            if (isset($error['error']['message'])) {
                echo "<strong>Error:</strong> " . htmlspecialchars($error['error']['message']) . "<br>";
            } else {
                echo "<pre>" . htmlspecialchars(substr($response, 0, 500)) . "</pre>";
            }
            echo "</div>";
        }
        if ($curl_error) {
            echo "<p><strong>CURL Error:</strong> $curl_error</p>";
        }
    }
    
    echo "</div>";
}

echo "<hr>";

if ($working_model) {
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745; margin: 20px 0;'>";
    echo "<h3 style='color: #155724; margin-top: 0;'>✅ Kết quả:</h3>";
    echo "<p style='font-size: 16px;'><strong>API Key của bạn hoạt động với model: $working_model</strong></p>";
    echo "<p>👉 Bây giờ bạn có thể thêm API key này vào file <code>config/gemini_config.php</code></p>";
    echo "<p>Hoặc truy cập: <a href='setup_gemini_key.php' style='color: #0066cc; font-weight: bold;'>setup_gemini_key.php</a> để thêm qua form</p>";
    echo "</div>";
    
    // Tự động cập nhật config
    echo "<h3>3. Tự động cập nhật config:</h3>";
    $config_file = __DIR__ . '/config/gemini_config.php';
    if (file_exists($config_file)) {
        $config_content = "<?php
/**
 * Cấu hình API Key cho Google Gemini AI
 * File này được tạo tự động
 */

\$GEMINI_API_KEY = '$api_key';

?>";
        
        if (file_put_contents($config_file, $config_content)) {
            echo "<p style='color: green;'>✅ <strong>Đã tự động lưu API key vào config/gemini_config.php</strong></p>";
            echo "<p>👉 Bây giờ bạn có thể thử lại tính năng tạo bài viết bằng AI trong admin panel!</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Không thể tự động lưu. Vui lòng thêm thủ công vào file config/gemini_config.php:</p>";
            echo "<pre style='background: #f4f4f4; padding: 10px; border-radius: 5px;'>\$GEMINI_API_KEY = '$api_key';</pre>";
        }
    }
} else {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 8px; border-left: 4px solid #dc3545; margin: 20px 0;'>";
    echo "<h3 style='color: #721c24; margin-top: 0;'>❌ Kết quả:</h3>";
    echo "<p style='font-size: 16px;'><strong>API Key của bạn không hoạt động với bất kỳ model nào.</strong></p>";
    echo "<p><strong>Nguyên nhân có thể:</strong></p>";
    echo "<ul>";
    echo "<li>API key chưa được kích hoạt Generative Language API trong Google Cloud Console</li>";
    echo "<li>API key không có quyền truy cập các model Gemini</li>";
    echo "<li>Project chưa được thiết lập billing (một số models yêu cầu)</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div style='background: #e8f4f8; padding: 20px; border-radius: 8px; border-left: 4px solid #3498db; margin: 20px 0;'>";
    echo "<h3 style='color: #2c3e50; margin-top: 0;'>📖 Hướng dẫn kích hoạt Generative Language API:</h3>";
    echo "<ol style='line-height: 2;'>";
    echo "<li><strong>Chọn Project:</strong><br>";
    echo "   → Truy cập: <a href='https://console.cloud.google.com/' target='_blank'>Google Cloud Console</a><br>";
    echo "   → Ở thanh trên cùng, chọn project tương ứng với API key của bạn<br>";
    echo "   → (Nếu chưa có project, tạo mới: Click dropdown project → 'New Project')</li>";
    echo "<li><strong>Kích hoạt API:</strong><br>";
    echo "   → Truy cập: <a href='https://console.cloud.google.com/apis/library' target='_blank'>APIs Library</a><br>";
    echo "   → Tìm kiếm: <strong>\"Generative Language API\"</strong> hoặc <strong>\"Gemini API\"</strong><br>";
    echo "   → Click vào API → Click nút <strong>\"Enable\"</strong> hoặc <strong>\"Kích hoạt\"</strong><br>";
    echo "   → Đợi vài phút để API được kích hoạt</li>";
    echo "<li><strong>Kiểm tra:</strong><br>";
    echo "   → Vào \"APIs & Services\" → \"Enabled APIs\"<br>";
    echo "   → Xem \"Generative Language API\" có trong danh sách không</li>";
    echo "<li><strong>Thiết lập Billing (nếu cần):</strong><br>";
    echo "   → Một số models có thể yêu cầu billing<br>";
    echo "   → Vào \"Billing\" → Thiết lập tài khoản thanh toán (có thể miễn phí trong giới hạn)<br>";
    echo "   → Hoặc sử dụng tier miễn phí</li>";
    echo "<li><strong>Test lại:</strong><br>";
    echo "   → Làm mới trang này sau 5-10 phút<br>";
    echo "   → Hoặc thử lại tính năng tạo bài viết bằng AI</li>";
    echo "</ol>";
    echo "<p style='background: #fff3cd; padding: 10px; border-radius: 5px; margin-top: 15px;'>";
    echo "<strong>⚠️ Lưu ý:</strong> Đảm bảo API key của bạn thuộc về project đã kích hoạt Generative Language API. Nếu không, hãy tạo API key mới từ project đã kích hoạt API.";
    echo "</p>";
    echo "</div>";
}

echo "<hr>";
echo "<p style='text-align: center; color: #999; font-size: 12px;'>Script test - Xóa file này sau khi đã hoàn tất để bảo mật</p>";
?>

