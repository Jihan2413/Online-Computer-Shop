<?php
require_once __DIR__ . '/../config/session.php';
if (isset($_SESSION['user_id'])) {
    header('Location: ' . base_url('index.php'));
    exit;
}

$errors   = $_SESSION['form_errors'] ?? [];
$old      = $_SESSION['old_input']   ?? [];
$success  = $_SESSION['flash_success'] ?? '';
unset($_SESSION['form_errors'], $_SESSION['old_input'], $_SESSION['flash_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register | PCShop</title>
    <link rel="stylesheet" href="<?= base_url('public/styles/navbar.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/styles/auth.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/styles/footer.css') ?>" />
    <script src="https://kit.fontawesome.com/8f7b27f9d3.js" crossorigin="anonymous"></script>
    <script>window.APP_BASE_URL = '<?= BASE_URL ?>';</script>
</head>
<body>

<?php include __DIR__ . '/layouts/navbar.php'; ?>

<div class="auth-container">
    <h2>Create Account</h2>

    <?php if ($success): ?>
        <div class="success-message"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($errors['formErr'])): ?>
        <div class="error-msg"><?= htmlspecialchars($errors['formErr']) ?></div>
    <?php endif; ?>

    <form onsubmit="return validateRegisterForm()"
          action="<?= base_url('controllers/auth_controller.php') ?>"
          method="POST"
          novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>" />

        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name"
                   placeholder="John Doe"
                   value="<?= htmlspecialchars($old['name'] ?? '') ?>" />
            <?php if (!empty($errors['nameErr'])): ?>
                <small class="err"><?= htmlspecialchars($errors['nameErr']) ?></small>
            <?php endif; ?>
            <span class="js-error" id="nameError"></span>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email"
                   placeholder="you@example.com"
                   value="<?= htmlspecialchars($old['email'] ?? '') ?>" />
            <?php if (!empty($errors['emailErr'])): ?>
                <small class="err"><?= htmlspecialchars($errors['emailErr']) ?></small>
            <?php endif; ?>
            <span class="js-error" id="emailError"></span>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-eye">
                <input type="password" id="password" name="password" placeholder="Min 8 characters" />
                <i class="fas fa-eye toggle-pw" data-target="password"></i>
            </div>
            <?php if (!empty($errors['passErr'])): ?>
                <small class="err"><?= htmlspecialchars($errors['passErr']) ?></small>
            <?php endif; ?>
            <span class="js-error" id="passwordError"></span>
        </div>

        <div class="form-group">
            <label for="confirm">Confirm Password</label>
            <div class="input-eye">
                <input type="password" id="confirm" name="confirm" placeholder="Repeat password" />
                <i class="fas fa-eye toggle-pw" data-target="confirm"></i>
            </div>
            <?php if (!empty($errors['confirmErr'])): ?>
                <small class="err"><?= htmlspecialchars($errors['confirmErr']) ?></small>
            <?php endif; ?>
            <span class="js-error" id="confirmError"></span>
        </div>

        <div class="form-group">
            <label for="role">Account Type</label>
            <select id="role" name="role">
                <option value="customer" <?= ($old['role'] ?? '') === 'customer' ? 'selected' : '' ?>>Customer</option>
                <option value="admin"    <?= ($old['role'] ?? '') === 'admin'    ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <button type="submit" name="register">Create Account</button>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>

<script src="<?= base_url('public/scripts/navbar.js') ?>"></script>
<script src="<?= base_url('public/scripts/js-validation.js') ?>"></script>
</body>
</html>
