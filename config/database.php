<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "pc_shop_db";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection Failed");
}

?>