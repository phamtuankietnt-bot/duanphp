<?php
class Favorite {
    private $conn;
    private $table_name = "favorites";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Thêm sản phẩm vào danh sách yêu thích
    public function addToFavorites($user_id, $product_id) {
        // Kiểm tra xem đã yêu thích chưa
        if ($this->isFavorite($user_id, $product_id)) {
            return false; // Đã yêu thích rồi
        }

        $query = "INSERT INTO " . $this->table_name . " (user_id, product_id, created_at) VALUES (?, ?, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->bindParam(2, $product_id);
        return $stmt->execute();
    }

    // Xóa sản phẩm khỏi danh sách yêu thích
    public function removeFromFavorites($user_id, $product_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE user_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->bindParam(2, $product_id);
        return $stmt->execute();
    }

    // Kiểm tra sản phẩm đã được yêu thích chưa
    public function isFavorite($user_id, $product_id) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE user_id = ? AND product_id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->bindParam(2, $product_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Lấy tất cả sản phẩm yêu thích của user
    public function getFavoritesByUserId($user_id) {
        $query = "SELECT f.*, p.name, p.price, p.image, p.description, c.name as category_name
                  FROM " . $this->table_name . " f
                  JOIN products p ON f.product_id = p.id
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE f.user_id = ?
                  ORDER BY f.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm số lượng sản phẩm yêu thích của user
    public function getFavoriteCount($user_id) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ? $result['total'] : 0;
    }
}
?>

