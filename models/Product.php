<?php

class Product {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getProducts(){

        $sql = "SELECT products.*, categories.name AS category_name,
                brands.name AS brand_name
                FROM products
                JOIN categories ON products.category_id = categories.id
                JOIN brands ON products.brand_id = brands.id";

        return $this->conn->query($sql);
    }

    public function addProduct($data){

        $stmt = $this->conn->prepare("
            INSERT INTO products
            (name, description, manufacturer_review, price, category_id, brand_id, image_path, stock)
            VALUES(?,?,?,?,?,?,?,?)
        ");

        $stmt->bind_param(
            "sssdiisi",
            $data['name'],
            $data['description'],
            $data['manufacturer_review'],
            $data['price'],
            $data['category_id'],
            $data['brand_id'],
            $data['image_path'],
            $data['stock']
        );

        return $stmt->execute();
    }

}
?>