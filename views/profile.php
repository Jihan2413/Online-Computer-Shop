<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';

// Auth gate
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . base_url('views/login.php'));
    exit;
}

$userModel = new UserModel($conn);
$user      = $userModel->getUserById($_SESSION['user_id']);

$errors  = $_SESSION['form_errors']  ?? [];
$success = $_SESSION['flash_success'] ?? '';
unset($_SESSION['form_errors'], $_SESSION['flash_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Profile | PCShop</title>
    <link rel="stylesheet" href="<?= base_url('public/styles/navbar.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/styles/profile.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/styles/footer.css') ?>" />
    <script src="https://kit.fontawesome.com/8f7b27f9d3.js" crossorigin="anonymous"></script>
    <script>window.APP_BASE_URL = '<?= BASE_URL ?>';</script>
</head>
<body>

<?php include __DIR__ . '/layouts/navbar.php'; ?>

<div class="profile-page">

    <div class="profile-header">
        <div class="avatar-wrap">
            <?php if (!empty($user['profile_picture'])): ?>
                <img src="<?= base_url('public/' . htmlspecialchars($user['profile_picture'])) ?>" alt="Profile picture" class="avatar" />
            <?php else: ?>
                <div class="avatar-placeholder"><i class="fas fa-user"></i></div>
            <?php endif; ?>
        </div>
        <div>
            <h2><?= htmlspecialchars($user['name']) ?></h2>
            <span class="role-badge <?= $user['role'] ?>"><?= ucfirst($user['role']) ?></span>
            <p class="joined">Joined: <?= date('M d, Y', strtotime($user['created_at'])) ?></p>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="success-message"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($errors['formErr'])): ?>
        <div class="error-msg"><?= htmlspecialchars($errors['formErr']) ?></div>
    <?php endif; ?>

    <div class="profile-card">
        <h3>Edit Profile</h3>
        <form action="<?= base_url('controllers/profile_controller.php') ?>"
              method="POST"
              enctype="multipart/form-data"
              onsubmit="return validateProfileForm()">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>" />

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="pname" name="name"
                       value="<?= htmlspecialchars($user['name']) ?>" />
                <?php if (!empty($errors['nameErr'])): ?>
                    <small class="err"><?= htmlspecialchars($errors['nameErr']) ?></small>
                <?php endif; ?>
                <span class="js-error" id="pnameError"></span>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="pemail" name="email"
                       value="<?= htmlspecialchars($user['email']) ?>" />
                <?php if (!empty($errors['emailErr'])): ?>
                    <small class="err"><?= htmlspecialchars($errors['emailErr']) ?></small>
                <?php endif; ?>
                <span class="js-error" id="pemailError"></span>
            </div>

            <div class="form-group">
                <label for="profile_picture">Profile Picture <small>(JPEG/PNG/GIF/WEBP, max 2MB)</small></label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" />
                <?php if (!empty($errors['picErr'])): ?>
                    <small class="err"><?= htmlspecialchars($errors['picErr']) ?></small>
                <?php endif; ?>
                <span class="js-error" id="picError"></span>
            </div>

            <button type="submit" name="update_profile">Save Changes</button>
        </form>
    </div>

    <div class="profile-card">
        <h3>Change Password</h3>
        <form action="<?= base_url('controllers/profile_controller.php') ?>"
              method="POST"
              onsubmit="return validatePasswordForm()">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>" />

            <div class="form-group">
                <label for="current_password">Current Password</label>
                <div class="input-eye">
                    <input type="password" id="current_password" name="current_password" placeholder="Current password" />
                    <i class="fas fa-eye toggle-pw" data-target="current_password"></i>
                </div>
                <?php if (!empty($errors['currentErr'])): ?>
                    <small class="err"><?= htmlspecialchars($errors['currentErr']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <div class="input-eye">
                    <input type="password" id="new_password" name="new_password" placeholder="Min 8 characters" />
                    <i class="fas fa-eye toggle-pw" data-target="new_password"></i>
                </div>
                <?php if (!empty($errors['newPassErr'])): ?>
                    <small class="err"><?= htmlspecialchars($errors['newPassErr']) ?></small>
                <?php endif; ?>
                <span class="js-error" id="newPassError"></span>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <div class="input-eye">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat new password" />
                    <i class="fas fa-eye toggle-pw" data-target="confirm_password"></i>
                </div>
                <?php if (!empty($errors['confirmErr'])): ?>
                    <small class="err"><?= htmlspecialchars($errors['confirmErr']) ?></small>
                <?php endif; ?>
                <span class="js-error" id="confirmPassError"></span>
            </div>

            <button type="submit" name="change_password">Update Password</button>
        </form>
    </div>

</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>

<script src="<?= base_url('public/scripts/navbar.js') ?>"></script>
<script src="<?= base_url('public/scripts/js-validation.js') ?>"></script>
</body>
</html>
