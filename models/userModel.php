<?php
    require_once('/../config/db.php');

    function loginUser($username, $password) {
        $con  = getConnection();
        $sql  = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user   = mysqli_fetch_assoc($result);
        mysqli_close($con);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    function registerUser($username, $password, $email) {
        $con  = getConnection();

        $sqlCheck = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmtCheck = mysqli_prepare($con, $sqlCheck);
        mysqli_stmt_bind_param($stmtCheck, "ss", $username, $email);
        mysqli_stmt_execute($stmtCheck);
        $resultCheck = mysqli_stmt_get_result($stmtCheck);
        if (mysqli_num_rows($resultCheck) > 0) {
            mysqli_close($con);
            return false;
        }

        $hash   = password_hash($password, PASSWORD_DEFAULT);
        $sql    = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt   = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $username, $hash, $email);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_close($con);
        return $ok;
    }
?>
