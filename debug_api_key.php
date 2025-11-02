<?php
/**
 * Script debug chi tiết để kiểm tra API key
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 Debug API Key Chi Tiết</h2>";
echo "<hr>";

// 1. Kiểm tra file config
$config_file = __DIR__ . '/config/gemini_config.php';
echo "<h3>1. Kiểm tra file config:</h3>";
if (file_exists($config_file)) {
    echo "✅ File tồn tại: <code>$config_file</code><br>";
    $content = file_get_contents($config_file);
    echo "<strong>Nội dung file (raw):</strong><br>";
    echo "<pre style='background: #f4f4f4; padding: 10px; border: 1px solid #ddd; max-height: 300px; overflow: auto;'>";
    echo htmlspecialchars($content);
    echo "</pre>";
} else {
    echo "❌ File KHÔNG tồn tại!<br>";
    exit;
}

echo "<hr>";

// 2. Parse API key bằng regex
echo "<h3>2. Parse API key bằng regex:</h3>";
$patterns = [
    "/\\\$GEMINI_API_KEY\s*=\s*['\"]([^'\"]+)['\"]\s*;/",
    "/\\\$GEMINI_API_KEY\s*=\s*['\"]([^'\"]+)['\"];?/",
    "/\\\$GEMINI_API_KEY\s*=\s*([^;]+);/"
];

$found_key = null;
foreach ($patterns as $i => $pattern) {
    if (preg_match($pattern, $content, $matches)) {
        $found_key = trim($matches[1], " \t\n\r\0\x0B\"'");
        echo "✅ Pattern " . ($i+1) . " match thành công!<br>";
        echo "Key tìm thấy (masked): <code>" . substr($found_key, 0, 10) . "...</code><br>";
        echo "Độ dài: " . strlen($found_key) . " ký tự<br>";
        break;
    }
}

if (empty($found_key)) {
    echo "❌ Không tìm thấy API key bằng regex!<br>";
}

echo "<hr>";

// 3. Require file và lấy biến
echo "<h3>3. Require file và lấy biến:</h3>";
$GEMINI_API_KEY = '';
require $config_file;
if (isset($GEMINI_API_KEY)) {
    echo "✅ Biến \$GEMINI_API_KEY tồn tại<br>";
    echo "Giá trị (masked): <code>" . (!empty($GEMINI_API_KEY) ? substr($GEMINI_API_KEY, 0, 10) . "..." : 'EMPTY') . "</code><br>";
    echo "Độ dài: " . strlen($GEMINI_API_KEY) . " ký tự<br>";
    echo "Is empty: " . (empty($GEMINI_API_KEY) ? 'YES ❌' : 'NO ✅') . "<br>";
    
    // Kiểm tra khoảng trắng
    if ($GEMINI_API_KEY !== trim($GEMINI_API_KEY)) {
        echo "⚠️ Có khoảng trắng thừa!<br>";
    }
} else {
    echo "❌ Biến \$GEMINI_API_KEY KHÔNG tồn tại!<br>";
}

echo "<hr>";

// 4. Test với GeminiAI class
echo "<h3>4. Test với GeminiAI class:</h3>";
require_once 'config/gemini.php';

$gemini = new GeminiAI();
$reflection = new ReflectionClass($gemini);
$property = $reflection->getProperty('api_key');
$property->setAccessible(true);
$loaded_key = $property->getValue($gemini);

if (!empty($loaded_key)) {
    echo "✅ API key được load vào class!<br>";
    echo "Key (masked): <code>" . substr($loaded_key, 0, 10) . "...</code><br>";
    echo "Độ dài: " . strlen($loaded_key) . " ký tự<br>";
    
    // Kiểm tra format
    if (!preg_match('/^AIzaSy/', $loaded_key)) {
        echo "⚠️ API key không bắt đầu bằng 'AIzaSy' - có thể không đúng format!<br>";
    }
} else {
    echo "❌ API key KHÔNG được load vào class!<br>";
}

echo "<hr>";

// 5. Test API key trực tiếp với Gemini API
if (!empty($loaded_key)) {
    echo "<h3>5. Test API key với Gemini API:</h3>";
    
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
    
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . urlencode($loaded_key);
    
    echo "URL (masked): <code>" . substr($url, 0, 80) . "...</code><br><br>";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($test_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    $test_response = curl_exec($ch);
    $test_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);
    
    echo "HTTP Code: <strong>$test_http_code</strong><br>";
    
    if ($test_http_code == 200) {
        echo "✅ API Key hợp lệ! Kết nối thành công!<br>";
        $result = json_decode($test_response, true);
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            echo "Response: " . htmlspecialchars(substr($result['candidates'][0]['content']['parts'][0]['text'], 0, 100)) . "...<br>";
        }
    } else {
        echo "❌ API Key không hợp lệ!<br>";
        if ($test_response) {
            $error = json_decode($test_response, true);
            echo "<strong>Error details:</strong><br>";
            echo "<pre style='background: #fee; padding: 10px; border-left: 4px solid #e74c3c;'>";
            print_r($error);
            echo "</pre>";
        }
        if ($curl_error) {
            echo "CURL Error: $curl_error<br>";
        }
    }
}

echo "<hr>";

// Hướng dẫn fix
echo "<h3>📝 Cách sửa lỗi:</h3>";
echo "<div style='background: #e8f4f8; padding: 15px; border-left: 4px solid #3498db;'>";
echo "<strong>Nếu API key vẫn không hoạt động:</strong><br><br>";
echo "1. <strong>Tạo API key mới:</strong><br>";
echo "   - Truy cập: <a href='https://makersuite.google.com/app/apikey' target='_blank'>Google AI Studio</a><br>";
echo "   - Xóa API key cũ và tạo mới<br><br>";
echo "2. <strong>Thêm API key vào file:</strong><br>";
echo "   - Mở <code>config/gemini_config.php</code><br>";
echo "   - Dòng 21: <code>\$GEMINI_API_KEY = 'YOUR_NEW_API_KEY_HERE';</code><br>";
echo "   - Đảm bảo có dấu nháy đơn bao quanh<br><br>";
echo "3. <strong>Kiểm tra:</strong><br>";
echo "   - API key phải bắt đầu bằng <code>AIzaSy</code><br>";
echo "   - Không có khoảng trắng thừa<br>";
echo "   - Độ dài thường khoảng 39 ký tự<br>";
echo "</div>";

?>

