<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../models/AuthModel.php';

$authModel = new AuthModel($conn);

function redirectWithErrors(string $page, array $errors, array $old = []): void
{
    $_SESSION['form_errors'] = $errors;
    if ($old) {
        $_SESSION['old_input'] = $old;
    }
    header('Location: ' . base_url($page));
    exit;
}

function sanitize(array $fields): array
{
    return array_map(fn ($value) => trim($value ?? ''), $fields);
}

function isValidName(string $name): bool
{
    return preg_match('/^[a-zA-Z.\s]+$/', $name);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $input = sanitize([
        'name'     => $_POST['name']     ?? '',
        'email'    => $_POST['email']    ?? '',
        'password' => $_POST['password'] ?? '',
        'confirm'  => $_POST['confirm']  ?? '',
    ]);
    $name = $input['name'];
    $email = $input['email'];
    $password = $input['password'];
    $confirm = $input['confirm'];
    $role     = in_array($_POST['role'] ?? 'customer', ['admin', 'customer'], true)
        ? $_POST['role']
        : 'customer';

    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        redirectWithErrors('views/register.php', ['formErr' => 'Invalid session token. Please try again.'], [
            'name' => $name,
            'email' => $email,
            'role' => $role,
        ]);
    }

    $errors = [];
    if ($name === '') {
        $errors['nameErr'] = 'Name is required.';
    } elseif (!isValidName($name)) {
        $errors['nameErr'] = 'Only letters, spaces, and dots allowed.';
    }

    if ($email === '') {
        $errors['emailErr'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['emailErr'] = 'Invalid email format.';
    }

    if ($password === '') {
        $errors['passErr'] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors['passErr'] = 'Password must be at least 8 characters.';
    }

    if ($confirm !== $password) {
        $errors['confirmErr'] = 'Passwords do not match.';
    }

    if ($errors) {
        redirectWithErrors('views/register.php', $errors, [
            'name' => $name,
            'email' => $email,
            'role' => $role,
        ]);
    }

    $result = $authModel->register($name, $email, $password, $role);
    if ($result['success']) {
        $_SESSION['flash_success'] = 'Registration successful! Please log in.';
        header('Location: ' . base_url('views/login.php'));
        exit;
    }

    redirectWithErrors('views/register.php', [
        $result['error'] . 'Err' => $result['message'],
    ], [
        'name' => $name,
        'email' => $email,
        'role' => $role,
    ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $input = sanitize([
        'email'    => $_POST['email']    ?? '',
        'password' => $_POST['password'] ?? '',
    ]);
    $email    = $input['email'];
    $password = $input['password'];
    $remember = isset($_POST['remember_me']);

    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        redirectWithErrors('views/login.php', ['formErr' => 'Invalid session token. Please try again.']);
    }

    $errors = [];
    if ($email === '') {
        $errors['emailErr'] = 'Email is required.';
    }
    if ($password === '') {
        $errors['passErr'] = 'Password is required.';
    }
    if ($errors) {
        redirectWithErrors('views/login.php', $errors);
    }

    $user = $authModel->login($email, $password);
    if (!$user) {
        redirectWithErrors('views/login.php', ['loginErr' => 'Invalid email or password.']);
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['profile_picture'] = $user['profile_picture'];

    if ($remember) {
        $token = bin2hex(random_bytes(32));
        $authModel->saveRememberToken($user['id'], $token);
        setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
    }

    $_SESSION['flash_success'] = 'Welcome back, ' . htmlspecialchars($user['name']) . '!';
    header('Location: ' . ($user['role'] === 'admin' ? base_url('views/admin_dashboard.php') : base_url('index.php')));
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {

    if (isset($_SESSION['user_id'])) {
        $authModel->clearRememberToken($_SESSION['user_id']);
    }

    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/');
    }

    session_unset();
    session_destroy();

    header('Location: ' . base_url('views/login.php'));
    exit;
}
?>
