<?php
class Voucher {
    private $conn;
    private $table_name = "vouchers";

    public $id;
    public $code;
    public $name;
    public $description;
    public $discount_type;
    public $discount_value;
    public $min_order_amount;
    public $max_discount_amount;
    public $usage_limit;
    public $used_count;
    public $start_date;
    public $end_date;
    public $is_active;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy 2 voucher tốt nhất phù hợp với đơn hàng
    public function getActiveVouchers($order_amount = 0) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE is_active = 1 
                  AND start_date <= NOW() 
                  AND end_date >= NOW() 
                  AND (usage_limit IS NULL OR used_count < usage_limit)
                  AND min_order_amount <= ?
                  ORDER BY 
                    CASE 
                        WHEN discount_type = 'percentage' THEN discount_value 
                        ELSE (discount_value / ?) * 100 
                    END DESC,
                    min_order_amount ASC
                  LIMIT 2";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $order_amount);
        $stmt->bindParam(2, $order_amount);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy voucher theo code
    public function getVoucherByCode($code) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE code = ? 
                  AND is_active = 1 
                  AND start_date <= NOW() 
                  AND end_date >= NOW()";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $code);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->code = $row['code'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->discount_type = $row['discount_type'];
            $this->discount_value = $row['discount_value'];
            $this->min_order_amount = $row['min_order_amount'];
            $this->max_discount_amount = $row['max_discount_amount'];
            $this->usage_limit = $row['usage_limit'];
            $this->used_count = $row['used_count'];
            $this->start_date = $row['start_date'];
            $this->end_date = $row['end_date'];
            $this->is_active = $row['is_active'];
            return true;
        }
        return false;
    }

    // Kiểm tra voucher có hợp lệ không
    public function isValidVoucher($code, $order_amount) {
        if (!$this->getVoucherByCode($code)) {
            return false;
        }
        
        // Kiểm tra số tiền tối thiểu
        if ($order_amount < $this->min_order_amount) {
            return false;
        }
        
        // Kiểm tra giới hạn sử dụng
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }
        
        return true;
    }

    // Tính toán giảm giá
    public function calculateDiscount($order_amount) {
        if ($this->discount_type == 'percentage') {
            $discount = ($order_amount * $this->discount_value) / 100;
            
            // Áp dụng giới hạn tối đa nếu có
            if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
                $discount = $this->max_discount_amount;
            }
            
            return $discount;
        } else {
            // Fixed amount
            return $this->discount_value;
        }
    }

    // Tăng số lần sử dụng
    public function incrementUsage() {
        $query = "UPDATE " . $this->table_name . " 
                  SET used_count = used_count + 1 
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }
}
?>
