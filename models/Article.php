<?php
class Article {
    private $conn;
    private $table_name = "articles";

    public $id;
    public $title;
    public $content;
    public $excerpt;
    public $image;
    public $author;
    public $category;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả bài viết
    public function getAllArticles() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE status = 'published' ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy bài viết theo ID
    public function getArticleById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? AND status = 'published' LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->title = $row['title'];
            $this->content = $row['content'];
            $this->excerpt = $row['excerpt'];
            $this->image = $row['image'];
            $this->author = $row['author'];
            $this->category = $row['category'];
            $this->status = $row['status'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }
        return false;
    }

    // Lấy bài viết theo danh mục
    public function getArticlesByCategory($category) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE category = ? AND status = 'published' ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category);
        $stmt->execute();
        return $stmt;
    }

    // Tìm kiếm bài viết
    public function searchArticles($keyword) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE (title LIKE ? OR content LIKE ? OR excerpt LIKE ?) AND status = 'published' ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(1, $keyword);
        $stmt->bindParam(2, $keyword);
        $stmt->bindParam(3, $keyword);
        $stmt->execute();
        return $stmt;
    }

    // Lấy bài viết mới nhất
    public function getLatestArticles($limit = 6) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE status = 'published' ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // Lấy bài viết liên quan
    public function getRelatedArticles($category, $exclude_id, $limit = 3) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE category = ? AND id != ? AND status = 'published' ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category);
        $stmt->bindParam(2, $exclude_id);
        $stmt->bindParam(3, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
}
?>
