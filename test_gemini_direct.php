<?php
/**
 * Script test trực tiếp Gemini API để kiểm tra API key
 */

require_once 'config/gemini.php';

echo "<h2>🧪 Test trực tiếp Gemini API</h2>";
echo "<hr>";

// Đọc API key
$config_file = __DIR__ . '/config/gemini_config.php';
if (file_exists($config_file)) {
    require $config_file;
    $api_key = isset($GEMINI_API_KEY) ? $GEMINI_API_KEY : '';
} else {
    $api_key = '';
}

if (empty($api_key)) {
    echo "<p style='color: red;'>❌ API key chưa được cấu hình. Vui lòng thêm API key vào config/gemini_config.php</p>";
    exit;
}

echo "<p>✅ API key đã được load: <code>" . substr($api_key, 0, 10) . "...</code></p>";
echo "<hr>";

// Test với các model khác nhau
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

echo "<h3>Testing các models:</h3>";

foreach ($models as $model) {
    echo "<div style='margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px;'>";
    echo "<h4>🔍 Testing model: <strong>$model</strong></h4>";
    
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
    
    echo "<p><strong>HTTP Code:</strong> $http_code</p>";
    
    if ($http_code == 200) {
        echo "<p style='color: green;'>✅ <strong>THÀNH CÔNG!</strong> Model $model hoạt động!</p>";
        $result = json_decode($response, true);
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            echo "<p><strong>Response:</strong> " . htmlspecialchars(substr($result['candidates'][0]['content']['parts'][0]['text'], 0, 200)) . "...</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ <strong>LỖI!</strong></p>";
        if ($response) {
            $error = json_decode($response, true);
            echo "<pre style='background: #fee; padding: 10px; border-radius: 5px;'>";
            print_r($error);
            echo "</pre>";
        }
        if ($curl_error) {
            echo "<p><strong>CURL Error:</strong> $curl_error</p>";
        }
    }
    
    echo "</div>";
}

echo "<hr>";
echo "<h3>📝 Hướng dẫn khắc phục:</h3>";
echo "<div style='background: #e8f4f8; padding: 15px; border-left: 4px solid #3498db;'>";
echo "<strong>Nếu tất cả models đều lỗi 404:</strong><br><br>";
echo "1. <strong>Kiểm tra API đã được bật chưa:</strong><br>";
echo "   - Truy cập: <a href='https://console.cloud.google.com/apis/library' target='_blank'>Google Cloud Console - APIs</a><br>";
echo "   - Tìm và enable: <strong>Generative Language API</strong> hoặc <strong>Gemini API</strong><br><br>";
echo "2. <strong>Kiểm tra quyền API key:</strong><br>";
echo "   - Vào <a href='https://aistudio.google.com/app/api-keys' target='_blank'>Google AI Studio</a><br>";
echo "   - Đảm bảo API key có quyền truy cập Gemini API<br><br>";
echo "3. <strong>Tạo API key mới:</strong><br>";
echo "   - Tạo project mới trong Google Cloud<br>";
echo "   - Enable Generative Language API<br>";
echo "   - Tạo API key mới từ project đó<br>";
echo "</div>";

?>

