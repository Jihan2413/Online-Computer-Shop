<?php

require_once "../config/database.php";

$category_id = $_GET['category_id'];

$stmt = $conn->prepare("SELECT * FROM brands WHERE category_id=?");

$stmt->bind_param("i", $category_id);

$stmt->execute();

$result = $stmt->get_result();

$data = [];

while($row = $result->fetch_assoc()){

    $data[] = $row;
}

header("Content-Type: application/json");

echo json_encode($data);

?>