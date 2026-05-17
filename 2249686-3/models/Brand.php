<?php

class Brand {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getBrands(){

        return $this->conn->query("SELECT * FROM brands");
    }

    public function addBrand($name, $category_id){

        $stmt = $this->conn->prepare("INSERT INTO brands(name, category_id) VALUES(?, ?)");

        $stmt->bind_param("si", $name, $category_id);

        return $stmt->execute();
    }

}
?>