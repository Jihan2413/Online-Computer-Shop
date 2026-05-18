<?php

    session_start();
    require_once('/../models/userModel.php');

    if (!isset($_POST['submit'])) {
        header('location: ../views/login.php');
        exit;
    }

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    
    if ($username === '' || $password === '') {
        $_SESSION['error'] = 'Username and password are required.';
        header('location: ../views/login.php');
        exit;
    }

    $user = loginUser($username, $password);

    if ($user) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('location: ../index.php');
    } else {
        $_SESSION['error'] = 'Invalid username or password.';
        header('location: ../views/login.php');
    }
    exit;
?>
