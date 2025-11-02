<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $email;
    public $password;
    public $full_name;
    public $phone;
    public $address;
    public $role;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Đăng ký người dùng mới
    public function register($username, $email, $password, $full_name, $phone = '', $address = '') {
        // Kiểm tra email đã tồn tại chưa
        if ($this->emailExists($email)) {
            return false;
        }

        // Kiểm tra username đã tồn tại chưa
        if ($this->usernameExists($username)) {
            return false;
        }

        // Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO " . $this->table_name . " 
                 (username, email, password, full_name, phone, address, role, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, 'user', NOW())";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, $hashed_password);
        $stmt->bindParam(4, $full_name);
        $stmt->bindParam(5, $phone);
        $stmt->bindParam(6, $address);
        
        return $stmt->execute();
    }

    // Đăng nhập
    public function login($email, $password) {
        $query = "SELECT id, username, email, password, full_name, phone, address, role 
                  FROM " . $this->table_name . " 
                  WHERE email = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->email = $row['email'];
                $this->full_name = $row['full_name'];
                $this->phone = $row['phone'];
                $this->address = $row['address'];
                $this->role = $row['role'];
                return true;
            }
        }
        return false;
    }

    // Kiểm tra email đã tồn tại
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Kiểm tra username đã tồn tại
    public function usernameExists($username) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE username = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Lấy thông tin người dùng theo ID
    public function getUserById($id) {
        $query = "SELECT id, username, email, full_name, phone, address, role, created_at 
                  FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->full_name = $row['full_name'];
            $this->phone = $row['phone'];
            $this->address = $row['address'];
            $this->role = $row['role'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Cập nhật thông tin người dùng
    public function updateProfile($id, $full_name, $phone, $address) {
        $query = "UPDATE " . $this->table_name . " 
                 SET full_name = ?, phone = ?, address = ? 
                 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $full_name);
        $stmt->bindParam(2, $phone);
        $stmt->bindParam(3, $address);
        $stmt->bindParam(4, $id);
        return $stmt->execute();
    }

    // Đổi mật khẩu
    public function changePassword($id, $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE " . $this->table_name . " SET password = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $hashed_password);
        $stmt->bindParam(2, $id);
        return $stmt->execute();
    }
}
?>
