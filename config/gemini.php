<?php
// Config cho Google Gemini AI

class GeminiAI {
    private $api_key;
    // Sử dụng model gemini-pro (ổn định và được hỗ trợ rộng rãi)
    private $api_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';
    
    // Danh sách model để thử fallback
    private $fallback_models = [
        'gemini-pro',
        'gemini-1.5-pro',
        'gemini-1.5-flash'
    ];
    
    public function __construct($api_key = null) {
        // Ưu tiên: tham số truyền vào > biến môi trường > config file > inline config
        if ($api_key && !empty($api_key)) {
            $this->api_key = trim($api_key);
        } elseif (getenv('GEMINI_API_KEY')) {
            $this->api_key = trim(getenv('GEMINI_API_KEY'));
        } else {
            // Lấy từ file config riêng
            $config_file = __DIR__ . '/gemini_config.php';
            if (file_exists($config_file)) {
                // Sử dụng function scope để load biến
                $loaded_key = $this->loadApiKeyFromFile($config_file);
                $this->api_key = $loaded_key;
            } else {
                $this->api_key = '';
            }
            
            // Nếu vẫn không có, thử đọc từ file gemini.php trực tiếp (fallback)
            if (empty($this->api_key)) {
                // Có thể đặt API key trực tiếp ở đây nếu muốn (không khuyến khích)
                // $this->api_key = 'YOUR_API_KEY_HERE';
            }
        }
    }
    
    private function loadApiKeyFromFile($file_path) {
        if (!file_exists($file_path)) {
            return '';
        }
        
        // Đọc nội dung file
        $content = file_get_contents($file_path);
        
        // Tìm pattern: $GEMINI_API_KEY = '...' hoặc $GEMINI_API_KEY = "..."
        // Pattern 1: $GEMINI_API_KEY = 'value';
        if (preg_match("/\\\$GEMINI_API_KEY\s*=\s*['\"]([^'\"]+)['\"]\s*;/", $content, $matches)) {
            return trim($matches[1]);
        }
        
        // Pattern 2: $GEMINI_API_KEY = value; (không có quotes)
        if (preg_match("/\\\$GEMINI_API_KEY\s*=\s*([^;]+)\s*;/", $content, $matches)) {
            $value = trim($matches[1]);
            // Loại bỏ quotes nếu có
            $value = trim($value, "\"'");
            if (!empty($value)) {
                return $value;
            }
        }
        
        // Fallback: require file và lấy biến global
        try {
            // Tạo một scope riêng để tránh conflict
            $GEMINI_API_KEY = '';
            require $file_path;
            if (isset($GEMINI_API_KEY) && !empty($GEMINI_API_KEY)) {
                return trim($GEMINI_API_KEY);
            }
        } catch (Exception $e) {
            error_log('Error loading Gemini API key: ' . $e->getMessage());
        }
        
        return '';
    }
    
    // Tạo nội dung bài viết bằng AI
    public function generateArticle($topic, $category = 'Tin tức', $length = 'medium') {
        if (empty($this->api_key)) {
            return [
                'success' => false,
                'message' => 'API key chưa được cấu hình. Vui lòng truy cập <a href="setup_gemini_key.php" target="_blank">setup_gemini_key.php</a> để thêm API key của bạn. Hoặc mở file config/gemini_config.php và thêm API key vào biến $GEMINI_API_KEY. Lấy API key tại: https://makersuite.google.com/app/apikey'
            ];
        }
        
        $prompt = $this->buildPrompt($topic, $category, $length);
        
        $data = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $prompt
                        ]
                    ]
                ]
            ]
        ];
        
        // Thử với các model khác nhau, bắt đầu từ gemini-pro
        // Thứ tự ưu tiên: gemini-pro (cơ bản) -> gemini-1.5-pro -> gemini-1.5-flash
        $models_to_try = ['gemini-pro', 'gemini-1.5-pro', 'gemini-1.5-flash'];
        
        $response = null;
        $http_code = 0;
        $curl_error = '';
        $last_error = '';
        
        foreach ($models_to_try as $model) {
            $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . urlencode($this->api_key);
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($ch);
            curl_close($ch);
            
            // Nếu thành công, dừng lại
            if ($http_code == 200) {
                break;
            }
            
            // Nếu lỗi 404 (model không tìm thấy), thử model tiếp theo
            if ($http_code == 404) {
                $error_response = json_decode($response, true);
                $last_error = isset($error_response['error']['message']) ? $error_response['error']['message'] : 'Model not found';
                continue; // Thử model tiếp theo
            }
            
            // Nếu lỗi khác (400, 403, 500...), dừng lại không thử tiếp
            break;
        }
        
        if ($http_code != 200) {
            $error_details = '';
            if ($response) {
                $error_response = json_decode($response, true);
                if (isset($error_response['error']['message'])) {
                    $error_details = ': ' . $error_response['error']['message'];
                } elseif (isset($error_response['error'])) {
                    $error_details = ': ' . json_encode($error_response['error']);
                } else {
                    $error_details = ': ' . substr($response, 0, 200);
                }
            }
            
            if ($curl_error) {
                $error_details .= ' (CURL Error: ' . $curl_error . ')';
            }
            
            // Nếu tất cả models đều lỗi 404
            if ($http_code == 404) {
                return [
                    'success' => false,
                    'message' => 'Model không khả dụng. API key của bạn có thể không có quyền truy cập các model Gemini. Vui lòng kiểm tra quyền truy cập API key tại Google AI Studio hoặc tạo API key mới.'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Lỗi kết nối API: ' . $http_code . $error_details . '. Vui lòng kiểm tra API key và thử lại.'
            ];
        }
        
        $result = json_decode($response, true);
        
        // Kiểm tra lỗi trong response
        if (isset($result['error'])) {
            return [
                'success' => false,
                'message' => 'Lỗi từ API: ' . (isset($result['error']['message']) ? $result['error']['message'] : 'Unknown error')
            ];
        }
        
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            $generated_text = $result['candidates'][0]['content']['parts'][0]['text'];
            
            // Tách thành title, excerpt và content
            $parts = $this->parseGeneratedText($generated_text);
            
            return [
                'success' => true,
                'title' => $parts['title'],
                'excerpt' => $parts['excerpt'],
                'content' => $parts['content']
            ];
        }
        
        // Log response để debug
        error_log('Gemini API Response (no candidates): ' . json_encode($result));
        
        return [
            'success' => false,
            'message' => 'Không thể tạo nội dung. API không trả về dữ liệu. Vui lòng thử lại.'
        ];
    }
    
    private function buildPrompt($topic, $category, $length) {
        $length_text = $length == 'short' ? 'khoảng 300 từ' : ($length == 'long' ? 'khoảng 1000 từ' : 'khoảng 600 từ');
        
        return "Hãy viết một bài viết về chủ đề: '$topic' thuộc danh mục '$category' cho website bán giày thể thao.

Yêu cầu:
- Độ dài: $length_text
- Viết bằng tiếng Việt
- Nội dung hấp dẫn, chuyên nghiệp
- Có thể sử dụng emoji để làm cho bài viết sinh động hơn

Định dạng trả về:
TITLE: [Tiêu đề bài viết]
EXCERPT: [Đoạn tóm tắt ngắn gọn khoảng 100-150 từ]
CONTENT: [Nội dung chính của bài viết]";
    }
    
    private function parseGeneratedText($text) {
        $title = '';
        $excerpt = '';
        $content = '';
        
        // Tách theo format TITLE:, EXCERPT:, CONTENT:
        if (preg_match('/TITLE:\s*(.+?)(?=EXCERPT:|$)/is', $text, $title_match)) {
            $title = trim($title_match[1]);
        }
        
        if (preg_match('/EXCERPT:\s*(.+?)(?=CONTENT:|$)/is', $text, $excerpt_match)) {
            $excerpt = trim($excerpt_match[1]);
        }
        
        if (preg_match('/CONTENT:\s*(.+?)$/is', $text, $content_match)) {
            $content = trim($content_match[1]);
        }
        
        // Nếu không parse được, thử cách khác
        if (empty($title) || empty($content)) {
            $lines = explode("\n", $text);
            if (!empty($lines[0])) {
                $title = trim($lines[0]);
                // Loại bỏ TITLE: nếu có
                $title = preg_replace('/^TITLE:\s*/i', '', $title);
            }
            $content = trim($text);
            // Loại bỏ các tiêu đề format nếu có
            $content = preg_replace('/^(TITLE:|EXCERPT:|CONTENT:)\s*/im', '', $content);
            if (empty($excerpt)) {
                $excerpt = mb_substr(strip_tags($content), 0, 150) . '...';
            }
        }
        
        // Đảm bảo có giá trị mặc định
        if (empty($title)) {
            $title = 'Bài viết mới';
        }
        if (empty($content)) {
            $content = $text;
        }
        if (empty($excerpt)) {
            $excerpt = mb_substr(strip_tags($content), 0, 150) . '...';
        }
        
        return [
            'title' => trim($title),
            'excerpt' => trim($excerpt),
            'content' => trim($content)
        ];
    }
}
?>

