<?php
class Order {
    private $conn;
    private $table_name = "orders";
    private $order_items_table = "order_items";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Tạo đơn hàng mới
    public function createOrder($customer_info, $cart_items, $total_amount) {
        try {
            $this->conn->beginTransaction();
            
            // Tạo đơn hàng
            $query = "INSERT INTO " . $this->table_name . " 
                     (customer_name, customer_email, customer_phone, customer_address, 
                      payment_method, total_amount, status, created_at) 
                     VALUES (?, ?, ?, ?, ?, ?, 'processing', NOW())";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $customer_info['name']);
            $stmt->bindParam(2, $customer_info['email']);
            $stmt->bindParam(3, $customer_info['phone']);
            $stmt->bindParam(4, $customer_info['address']);
            $stmt->bindParam(5, $customer_info['payment_method']);
            $stmt->bindParam(6, $total_amount);
            
            if ($stmt->execute()) {
                $order_id = $this->conn->lastInsertId();
                
                // Thêm chi tiết đơn hàng
                foreach ($cart_items as $item) {
                    $this->addOrderItem($order_id, $item);
                }
                
                $this->conn->commit();
                return $order_id;
            } else {
                $this->conn->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Thêm chi tiết đơn hàng
    private function addOrderItem($order_id, $item) {
        $query = "INSERT INTO " . $this->order_items_table . " 
                 (order_id, product_id, product_name, product_price, quantity, total_price) 
                 VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $total_price = $item['price'] * $item['quantity'];
        
        $stmt->bindParam(1, $order_id);
        $stmt->bindParam(2, $item['product_id']);
        $stmt->bindParam(3, $item['name']);
        $stmt->bindParam(4, $item['price']);
        $stmt->bindParam(5, $item['quantity']);
        $stmt->bindParam(6, $total_price);
        
        return $stmt->execute();
    }

    // Lấy thông tin đơn hàng theo ID
    public function getOrderById($order_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $order_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết đơn hàng
    public function getOrderItems($order_id) {
        $query = "SELECT * FROM " . $this->order_items_table . " WHERE order_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $order_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus($order_id, $status) {
        $query = "UPDATE " . $this->table_name . " SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $status);
        $stmt->bindParam(2, $order_id);
        return $stmt->execute();
    }

    // Lấy tất cả đơn hàng
    public function getAllOrders() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy đơn hàng theo user ID
    public function getOrdersByUserId($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE customer_email = (SELECT email FROM users WHERE id = ?) 
                 ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Xóa đơn hàng
    public function deleteOrder($order_id) {
        try {
            $this->conn->beginTransaction();
            
            // Xóa chi tiết đơn hàng
            $query1 = "DELETE FROM " . $this->order_items_table . " WHERE order_id = ?";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bindParam(1, $order_id);
            $stmt1->execute();
            
            // Xóa đơn hàng
            $query2 = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(1, $order_id);
            $stmt2->execute();
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>
