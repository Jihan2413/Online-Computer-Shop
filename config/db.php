<?php
    $host   = "127.0.0.1";
    $dbname = "computer_shop";
    $dbuser = "root";
    $dbpass = "";        

    function getConnection() {
        global $host, $dbname, $dbuser, $dbpass;
        $con = mysqli_connect($host, $dbuser, $dbpass, $dbname);

        if (!$con) {
            die("DB connection failed: " . mysqli_connect_error());
        }

        return $con;
    }
?>
