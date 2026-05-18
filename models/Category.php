<?php

class Category {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all categories
    public function getAllCategories() {

        $sql = "SELECT * FROM categories";
        $result = $this->conn->query($sql);

        return $result;
    }

    // Add category
    public function addCategory($name, $parent_id) {

        $stmt = $this->conn->prepare("INSERT INTO categories(name, parent_id) VALUES(?, ?)");

        $stmt->bind_param("si", $name, $parent_id);

        return $stmt->execute();
    }

    // Delete category
    public function deleteCategory($id) {

        // check child categories
        $check = $this->conn->prepare("SELECT id FROM categories WHERE parent_id=?");
        $check->bind_param("i", $id);
        $check->execute();
        $result = $check->get_result();

        if($result->num_rows > 0){
            return "Cannot delete. Child categories exist.";
        }

        // check products
        $check2 = $this->conn->prepare("SELECT id FROM products WHERE category_id=?");
        $check2->bind_param("i", $id);
        $check2->execute();

        $result2 = $check2->get_result();

        if($result2->num_rows > 0){
            return "Cannot delete. Products exist.";
        }

        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id=?");
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

}
?>