<?php
require_once __DIR__ . '/../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . base_url('views/login.php'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard | PCShop</title>
    <link rel="stylesheet" href="<?= base_url('public/styles/navbar.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/styles/admin.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/styles/footer.css') ?>" />
    <script src="https://kit.fontawesome.com/8f7b27f9d3.js" crossorigin="anonymous"></script>
    <script>window.APP_BASE_URL = '<?= BASE_URL ?>';</script>
</head>
<body>

<?php include __DIR__ . '/layouts/navbar.php'; ?>

<div class="admin-page">
    <h2><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>
    </p>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>

<script src="<?= base_url('public/scripts/navbar.js') ?>"></script>
</body>
</html>
