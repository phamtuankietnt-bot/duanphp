<?php
// Helper function để quản lý session
function startSession() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// Helper function để lấy số lượng giỏ hàng
function getCartCount() {
    startSession();
    
    $cart_count = 0;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            if (is_array($item) && isset($item['quantity'])) {
                $cart_count += (int)$item['quantity'];
            }
        }
    }
    
    return $cart_count;
}

// Helper function để làm sạch giỏ hàng
function cleanCart() {
    startSession();
    
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        $clean_cart = [];
        foreach ($_SESSION['cart'] as $key => $item) {
            if (is_array($item) && isset($item['product_id'], $item['name'], $item['price'], $item['quantity'], $item['image'])) {
                // Thêm category mặc định nếu không có
                if (!isset($item['category'])) {
                    $item['category'] = 'Sneakers';
                }
                $clean_cart[$key] = $item;
            }
        }
        $_SESSION['cart'] = $clean_cart;
    } else {
        $_SESSION['cart'] = [];
    }
}
?>
