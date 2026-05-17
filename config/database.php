<?php

<<<<<<< HEAD
    $host   = "127.0.0.1";
    $dbname = "computer_shop";
    $dbuser = "root";
    $dbpass= "";

    function getConnection(){

        global $host, $dbname, $dbuser, $dbpass;

        $con = mysqli_connect(
            $host,
            $dbuser,
            $dbpass,
            $dbname
        );

        if(!$con){
            die("DB connection error: "
                . mysqli_connect_error());
        }

        mysqli_set_charset($con, "utf8mb4");

        return $con;
    }
=======
$host = "localhost";
$user = "root";
$password = "";
$database = "computer_shop";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection Failed");
}
>>>>>>> 5cad63106a4a7f0fe2fa3817e0e7089721b91306

?>