<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    require_once __DIR__ . '/../../../config/db.php';
    require_once __DIR__ . '/../../../models/AuthModel.php';
    $authModel = new AuthModel($conn);
    $user = $authModel->getUserByRememberToken($_COOKIE['remember_token']);
    if ($user) {
        session_regenerate_id(true);
        $_SESSION['user_id']         = $user['id'];
        $_SESSION['name']            = $user['name'];
        $_SESSION['role']            = $user['role'];
        $_SESSION['email']           = $user['email'];
        $_SESSION['profile_picture'] = $user['profile_picture'];
    } else {
        setcookie('remember_token', '', time() - 3600, '/');
    }
}

$currentPath = $_SERVER['REQUEST_URI'];
$role        = $_SESSION['role'] ?? 'guest';
$isLoggedIn  = isset($_SESSION['user_id']);
?>
<nav class="navbar">
    <a class="logo" href="<?= $isLoggedIn && $role === 'admin' ? base_url('views/admin_dashboard.php') : base_url('index.php') ?>">
        PC<span>Shop</span>
    </a>

    <div class="nav-links" id="nav-links">
        <ul>
            <li><a href="<?= base_url('index.php') ?>"
                class="<?= strpos($currentPath, 'index.php') !== false ? 'active' : '' ?>">Home</a></li>

            <?php if ($isLoggedIn && $role === 'admin'): ?>
                <li><a href="<?= base_url('views/admin_dashboard.php') ?>"
                    class="<?= strpos($currentPath, 'admin_dashboard') !== false ? 'active' : '' ?>">Dashboard</a></li>
            <?php endif; ?>

            <?php if ($isLoggedIn && $role === 'customer'): ?>
                <li><a href="<?= base_url('views/profile.php') ?>"
                    class="<?= strpos($currentPath, 'profile.php') !== false ? 'active' : '' ?>">
                    <i class="fas fa-user"></i> Profile</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="btn-container">
        <?php if ($isLoggedIn): ?>
            <a href="<?= base_url('views/profile.php') ?>" class="profile-btn" title="<?= htmlspecialchars($_SESSION['name']) ?>">
                <?php if (!empty($_SESSION['profile_picture'])): ?>
                    <img src="<?= base_url('public/' . htmlspecialchars($_SESSION['profile_picture'])) ?>" alt="Profile">
                <?php else: ?>
                    <i class="fas fa-user"></i>
                <?php endif; ?>
            </a>
            <a href="<?= base_url('controllers/auth_controller.php?action=logout') ?>" class="login-btn">Logout</a>
        <?php else: ?>
            <a href="<?= base_url('views/login.php') ?>" class="login-btn">Login</a>
            <a href="<?= base_url('views/register.php') ?>" class="register-nav-btn">Register</a>
        <?php endif; ?>

        <div class="mobile-menu-btn" id="mobile-menu-btn">
            <i class="fas fa-bars"></i>
        </div>

        <div class="mobile-menu-options hidden" id="mobile-menu-options">
            <ul>
                <li><a href="<?= base_url('index.php') ?>">Home</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="<?= base_url('views/profile.php') ?>">Profile</a></li>
                    <?php if ($role === 'admin'): ?>
                        <li><a href="<?= base_url('views/admin_dashboard.php') ?>">Dashboard</a></li>
                    <?php endif; ?>
                    <li><a href="<?= base_url('controllers/auth_controller.php?action=logout') ?>">Logout</a></li>
                <?php else: ?>
                    <li><a href="<?= base_url('views/login.php') ?>">Login</a></li>
                    <li><a href="<?= base_url('views/register.php') ?>">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
