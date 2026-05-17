<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "computer_shop";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection Failed");
}

?>