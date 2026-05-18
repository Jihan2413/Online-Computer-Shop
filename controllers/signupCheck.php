<?php


    session_start();
    require_once('/../models/userModel.php');

    if (!isset($_POST['submit'])) {
        header('location: ../views/signup.php');
        exit;
    }

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $email    = trim($_POST['email']    ?? '');

    if ($username === '' || $password === '' || $email === '') {
        $_SESSION['error'] = 'All fields are required.';
        header('location: ../views/signup.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Please enter a valid email address.';
        header('location: ../views/signup.php');
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = 'Password must be at least 6 characters.';
        header('location: ../views/signup.php');
        exit;
    }

    $ok = registerUser($username, $password, $email);

    if ($ok) {
        $_SESSION['success'] = 'Account created! Please log in.';
        header('location: ../views/login.php');
    } else {
        $_SESSION['error'] = 'Username or email already exists.';
        header('location: ../views/signup.php');
    }
    exit;
?>
