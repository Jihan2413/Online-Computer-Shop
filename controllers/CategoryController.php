<?php

require_once "../config/database.php";
require_once "../models/Category.php";

$category = new Category($conn);

if(isset($_POST['add'])){

    $name = htmlspecialchars($_POST['name']);
    $parent_id = $_POST['parent_id'];

    if(empty($parent_id)){
        $parent_id = NULL;
    }

    $category->addCategory($name, $parent_id);

    header("Location: ../views/category/index.php");
}

if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    $category->deleteCategory($id);

    header("Location: ../views/category/index.php");
}

// AJAX DELETE
if(isset($_POST['action']) && $_POST['action'] == "delete"){

    $id = $_POST['id'];

    if($category->delete($id)){

        echo json_encode([
            "status" => true
        ]);

    }else{

        echo json_encode([
            "status" => false
        ]);
    }
}
