<?php
/**
 * Form để thiết lập Gemini API Key
 * Truy cập: http://localhost/duanmau/setup_gemini_key.php
 */

session_start();

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['api_key'])) {
    $api_key = trim($_POST['api_key']);
    
    if (empty($api_key)) {
        $message = 'Vui lòng nhập API key!';
        $message_type = 'error';
    } else {
        // Loại bỏ khoảng trắng thừa và ký tự đặc biệt
        $api_key = trim($api_key);
        // Loại bỏ các ký tự không hợp lệ (chỉ giữ lại chữ, số, _, -)
        $api_key = preg_replace('/[^a-zA-Z0-9_-]/', '', $api_key);
        
        // Kiểm tra xem API key có đầy đủ không
        if (strlen($api_key) < 30) {
            $message = '❌ API key quá ngắn! API key phải có ít nhất 30 ký tự. Độ dài hiện tại: ' . strlen($api_key) . ' ký tự. Có thể bạn đã copy không đầy đủ API key. Vui lòng click vào biểu tượng "Copy" (sao chép) ở Google AI Studio để copy toàn bộ API key.';
            $message_type = 'error';
        } elseif (stripos($api_key, 'AlzaSy') === 0 || stripos($api_key, 'alzasy') === 0) {
            $message = '❌ Sai format! API key phải bắt đầu bằng "AIzaSy" (chữ A hoa, chữ I hoa, không phải "AlzaSy"). Vui lòng kiểm tra lại API key của bạn.';
            $message_type = 'error';
        } elseif (!preg_match('/^AIzaSy[a-zA-Z0-9_-]{30,40}$/', $api_key)) {
            // Validate API key format - AIzaSy + ít nhất 30 ký tự tiếp theo
            if (strlen($api_key) < 35 || strlen($api_key) > 45) {
                $message = '❌ API key không đúng độ dài. API key phải có khoảng 39 ký tự (bắt đầu bằng "AIzaSy" + khoảng 33 ký tự tiếp theo). Độ dài hiện tại: ' . strlen($api_key) . ' ký tự.';
            } elseif (stripos($api_key, 'AIzaSy') !== 0) {
                $message = '❌ API key phải bắt đầu bằng "AIzaSy" (A hoa, I hoa, z thường, a thường, S hoa, y thường). API key của bạn bắt đầu bằng: "' . substr($api_key, 0, min(10, strlen($api_key))) . '"';
            } else {
                $message = '❌ API key không đúng format. API key phải bắt đầu bằng "AIzaSy" và chỉ chứa chữ cái, số, dấu gạch dưới (_) hoặc dấu gạch ngang (-).';
            }
            $message_type = 'error';
        } else {
            // Lưu vào file config
            $config_content = "<?php
/**
 * Cấu hình API Key cho Google Gemini AI
 * File này được tạo tự động bởi setup_gemini_key.php
 * 
 * HƯỚNG DẪN LẤY API KEY:
 * 1. Truy cập: https://makersuite.google.com/app/apikey
 * 2. Đăng nhập bằng tài khoản Google
 * 3. Tạo API key mới
 * 4. Copy API key và dán vào biến \$GEMINI_API_KEY bên dưới
 * 
 * LƯU Ý: Không chia sẻ API key này với người khác!
 */

\$GEMINI_API_KEY = '" . addslashes($api_key) . "';

?>";

            $config_file = __DIR__ . '/config/gemini_config.php';
            
            if (file_put_contents($config_file, $config_content)) {
                $message = '✅ Đã lưu API key thành công! Bây giờ bạn có thể sử dụng tính năng tạo bài viết bằng AI.';
                $message_type = 'success';
            } else {
                $message = '❌ Không thể ghi file. Vui lòng kiểm tra quyền ghi file trong thư mục config/.';
                $message_type = 'error';
            }
        }
    }
}

// Đọc API key hiện tại
$current_key = '';
$config_file = __DIR__ . '/config/gemini_config.php';
if (file_exists($config_file)) {
    require $config_file;
    if (isset($GEMINI_API_KEY) && !empty($GEMINI_API_KEY)) {
        $current_key = $GEMINI_API_KEY;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thiết lập Gemini API Key</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
        }
        .help-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .info-box {
            background: #e8f4f8;
            border-left: 4px solid #3498db;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 13px;
            color: #2c3e50;
        }
        .info-box a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }
        .info-box a:hover {
            text-decoration: underline;
        }
        .current-key {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-family: monospace;
            font-size: 12px;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔑 Thiết lập Gemini API Key</h1>
        <p class="subtitle">Nhập API key của Google Gemini để sử dụng tính năng tạo bài viết bằng AI</p>
        
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="api_key">Google Gemini API Key *</label>
                <input 
                    type="text" 
                    id="api_key" 
                    name="api_key" 
                    value="<?php echo htmlspecialchars($current_key); ?>"
                    placeholder="AIzaSy..."
                    required
                    autocomplete="off"
                    onpaste="setTimeout(function() { 
                        var input = document.getElementById('api_key');
                        input.value = input.value.trim().replace(/[^a-zA-Z0-9_-]/g, '');
                    }, 100);"
                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9_-]/g, '');"
                >
                <div class="help-text">
                    ⚠️ <strong>Quan trọng:</strong><br>
                    • API key phải bắt đầu bằng <strong>"AIzaSy"</strong> (A hoa, I hoa, z thường, a thường, S hoa, y thường)<br>
                    • Độ dài: khoảng 39 ký tự (6 ký tự đầu "AIzaSy" + khoảng 33 ký tự tiếp theo)<br>
                    • <strong>Cách copy đúng:</strong> Tại Google AI Studio, click vào biểu tượng <strong>"Copy"</strong> (sao chép) ở cột bên phải của API key để copy toàn bộ API key. KHÔNG copy từ phần hiển thị "...o5so" vì đó chỉ là phần cuối!
                </div>
            </div>
            
            <button type="submit" class="btn">💾 Lưu API Key</button>
        </form>
        
        <div class="info-box">
            <strong>📖 Hướng dẫn lấy API Key:</strong><br><br>
            1. Truy cập <a href="https://makersuite.google.com/app/apikey" target="_blank">Google AI Studio</a><br>
            2. Đăng nhập bằng tài khoản Google của bạn<br>
            3. Click vào nút <strong>"Create API Key"</strong> hoặc <strong>"Get API Key"</strong><br>
            4. Copy API key (dạng: <code>AIzaSy...</code>)<br>
            5. Dán vào form trên và click <strong>"Lưu API Key"</strong><br><br>
            
            <strong>⚠️ Lưu ý bảo mật:</strong><br>
            - Không chia sẻ API key với người khác<br>
            - API key được lưu trong file <code>config/gemini_config.php</code><br>
            - Không commit API key lên Git (thêm vào .gitignore)
        </div>
        
        <?php if (!empty($current_key)): ?>
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                <strong>API Key hiện tại:</strong>
                <div class="current-key">
                    <?php 
                    $masked = substr($current_key, 0, 10) . str_repeat('*', strlen($current_key) - 15) . substr($current_key, -5);
                    echo htmlspecialchars($masked); 
                    ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 30px; text-align: center; color: #999; font-size: 12px;">
            <a href="index.php" style="color: #667eea; text-decoration: none;">← Quay lại trang chủ</a> | 
            <a href="test_gemini_api.php" style="color: #667eea; text-decoration: none;">Kiểm tra API Key</a>
        </div>
    </div>
</body>
</html>

