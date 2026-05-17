<?php

require_once "../config/database.php";
require_once "../models/Product.php";

$product = new Product($conn);

if(isset($_POST['add'])){

    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $manufacturer_review = htmlspecialchars($_POST['manufacturer_review']);

    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $brand_id = $_POST['brand_id'];
    $stock = $_POST['stock'];

    // Image Upload
    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];

    $image_path = "../public/uploads/products/" . time() . "_" . $image;

    move_uploaded_file($tmp_name, $image_path);

    $data = [
        "name" => $name,
        "description" => $description,
        "manufacturer_review" => $manufacturer_review,
        "price" => $price,
        "category_id" => $category_id,
        "brand_id" => $brand_id,
        "image_path" => $image_path,
        "stock" => $stock
    ];

    $product->addProduct($data);

    header("Location: ../views/product/index.php");
}

?>