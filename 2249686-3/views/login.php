<?php
require_once __DIR__ . '/../config/session.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ' . base_url('index.php'));
    exit;
}

$errors  = $_SESSION['form_errors'] ?? [];
$success = $_SESSION['flash_success'] ?? '';
unset($_SESSION['form_errors'], $_SESSION['flash_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | PCShop</title>
    <link rel="stylesheet" href="<?= base_url('public/styles/navbar.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/styles/auth.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/styles/footer.css') ?>" />
    <script src="https://kit.fontawesome.com/8f7b27f9d3.js" crossorigin="anonymous"></script>
    <script>window.APP_BASE_URL = '<?= BASE_URL ?>';</script>
</head>
<body>

<?php include __DIR__ . '/layouts/navbar.php'; ?>

<div class="auth-container">
    <h2>Welcome Back</h2>

    <?php if ($success): ?>
        <div class="success-message"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors['formErr'])): ?>
        <div class="error-msg"><?= htmlspecialchars($errors['formErr']) ?></div>
    <?php endif; ?>
    <?php if (!empty($errors['loginErr'])): ?>
        <div class="error-msg"><?= htmlspecialchars($errors['loginErr']) ?></div>
    <?php endif; ?>

    <form action="<?= base_url('controllers/auth_controller.php') ?>" method="POST" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>" />

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="you@example.com" />
            <?php if (!empty($errors['emailErr'])): ?>
                <small class="err"><?= htmlspecialchars($errors['emailErr']) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-eye">
                <input type="password" id="password" name="password" placeholder="Your password" />
                <i class="fas fa-eye toggle-pw" data-target="password"></i>
            </div>
            <?php if (!empty($errors['passErr'])): ?>
                <small class="err"><?= htmlspecialchars($errors['passErr']) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-group remember-row">
            <label class="checkbox-label">
                <input type="checkbox" name="remember_me" id="remember_me" />
                Remember me for 30 days
            </label>
        </div>

        <button type="submit" name="login">Login</button>
        <p>Don't have an account? <a href="<?= base_url('views/register.php') ?>">Register here</a></p>
    </form>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>

<script src="<?= base_url('public/scripts/navbar.js') ?>"></script>
</body>
</html>
