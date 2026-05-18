<?php
require_once __DIR__ . '/../config/db.php';

class HomeModel {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getTopCategories() {
        $stmt = $this->conn->prepare(
            "SELECT id, name FROM categories WHERE parent_id IS NULL ORDER BY name ASC"
        );
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        $stmt->close();
        return $categories;
    }

    public function getFeaturedProducts() {
        $stmt = $this->conn->prepare(
            "SELECT p.id, p.name, p.category_id, p.manufacturer_review, p.price, p.image_path,
                    COALESCE(SUM(oi.quantity), 0) AS total_sold
             FROM products p
             LEFT JOIN order_items oi ON p.id = oi.product_id
             GROUP BY p.id
             ORDER BY total_sold DESC, p.created_at DESC
             LIMIT 10"
        );
        $stmt->execute();
        $result = $stmt->get_result();
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        $stmt->close();
        return $products;
    }

    public function getCategoryById($id) {
        $stmt = $this->conn->prepare(
            "SELECT id, name, parent_id FROM categories WHERE id = ? LIMIT 1"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $category = $result->fetch_assoc();
        $stmt->close();
        return $category;
    }

    public function getProductsByCategoryId($categoryId) {
        $stmt = $this->conn->prepare(
            "SELECT id, name, manufacturer_review, price, image_path, category_id
             FROM products
             WHERE category_id = ?
             ORDER BY created_at DESC"
        );
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        $stmt->close();
        return $products;
    }
}
?>
