<?php
class Cart {
    private $conn;
    private $table_name = "cart_items";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Thêm sản phẩm vào giỏ hàng
    public function addToCart($product_id, $quantity = 1) {
        // Kiểm tra xem sản phẩm đã có trong giỏ chưa
        $existing_item = $this->getCartItem($product_id);
        
        if ($existing_item) {
            // Cập nhật số lượng
            $new_quantity = $existing_item['quantity'] + $quantity;
            return $this->updateCartItem($product_id, $new_quantity);
        } else {
            // Thêm mới vào giỏ
            $query = "INSERT INTO " . $this->table_name . " (product_id, quantity, created_at) VALUES (?, ?, NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $product_id);
            $stmt->bindParam(2, $quantity);
            return $stmt->execute();
        }
    }

    // Lấy tất cả sản phẩm trong giỏ hàng
    public function getCartItems() {
        $query = "SELECT ci.*, p.name, p.price, p.image, p.category 
                  FROM " . $this->table_name . " ci 
                  JOIN products p ON ci.product_id = p.id 
                  ORDER BY ci.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy một sản phẩm trong giỏ hàng
    public function getCartItem($product_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $product_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật số lượng sản phẩm
    public function updateCartItem($product_id, $quantity) {
        if ($quantity <= 0) {
            return $this->removeFromCart($product_id);
        }
        
        $query = "UPDATE " . $this->table_name . " SET quantity = ? WHERE product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $quantity);
        $stmt->bindParam(2, $product_id);
        return $stmt->execute();
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function removeFromCart($product_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $product_id);
        return $stmt->execute();
    }

    // Xóa tất cả sản phẩm trong giỏ hàng
    public function clearCart() {
        $query = "DELETE FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }

    // Tính tổng tiền
    public function getTotalAmount() {
        $items = $this->getCartItems();
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    // Đếm số lượng sản phẩm trong giỏ
    public function getCartCount() {
        $query = "SELECT SUM(quantity) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ? $result['total'] : 0;
    }
}
?>
