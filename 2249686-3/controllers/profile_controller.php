<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../models/UserModel.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . base_url('views/login.php'));
    exit;
}

$userModel = new UserModel($conn);
$userId    = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['form_errors'] = ['formErr' => 'Invalid session token. Please try again.'];
        header('Location: ' . base_url('views/profile.php'));
        exit;
    }

    $name   = trim($_POST['name']  ?? '');
    $email  = trim($_POST['email'] ?? '');
    $errors = [];

    if (empty($name)) {
        $errors['nameErr'] = 'Name is required.';
    } elseif (!preg_match("/^[a-zA-Z.\s]+$/", $name)) {
        $errors['nameErr'] = 'Only letters, spaces, and dots allowed.';
    }

    if (empty($email)) {
        $errors['emailErr'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['emailErr'] = 'Invalid email format.';
    }

    $picturePath = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $file     = $_FILES['profile_picture'];
        $maxSize  = 2 * 1024 * 1024; // 2MB
        $allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if ($file['size'] > $maxSize) {
            $errors['picErr'] = 'Image must be under 2MB.';
        } elseif (!in_array($mimeType, $allowed)) {
            $errors['picErr'] = 'Only JPEG, PNG, GIF, and WEBP images are allowed.';
        } else {
            $ext         = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename    = 'profile_' . $userId . '_' . time() . '.' . $ext;
            $uploadDir   = __DIR__ . '/../public/uploads/profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                $picturePath = 'uploads/profiles/' . $filename;
            } else {
                $errors['picErr'] = 'Failed to upload image.';
            }
        }
    }

    if (empty($errors)) {
        $result = $userModel->updateProfile($userId, $name, $email);

        if ($result['success']) {
            $_SESSION['name']  = $name;
            $_SESSION['email'] = $email;

            if ($picturePath) {
                $userModel->updateProfilePicture($userId, $picturePath);
                $_SESSION['profile_picture'] = $picturePath;
            }

            $_SESSION['flash_success'] = 'Profile updated successfully.';
        } else {
            $_SESSION['form_errors'] = ['emailErr' => $result['message']];
        }
    } else {
        $_SESSION['form_errors'] = $errors;
    }

    header('Location: ' . base_url('views/profile.php'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['form_errors'] = ['formErr' => 'Invalid session token. Please try again.'];
        header('Location: ' . base_url('views/profile.php'));
        exit;
    }

    $current = trim($_POST['current_password'] ?? '');
    $new     = trim($_POST['new_password']     ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');
    $errors  = [];

    if (empty($current)) {
        $errors['currentErr'] = 'Current password is required.';
    }

    if (empty($new)) {
        $errors['newPassErr'] = 'New password is required.';
    } elseif (strlen($new) < 8) {
        $errors['newPassErr'] = 'Password must be at least 8 characters.';
    }

    if ($new !== $confirm) {
        $errors['confirmErr'] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $result = $userModel->changePassword($userId, $current, $new);

        if ($result['success']) {
            $_SESSION['flash_success'] = 'Password changed successfully.';
        } else {
            $errors['currentErr'] = $result['message'];
            $_SESSION['form_errors'] = $errors;
        }
    } else {
        $_SESSION['form_errors'] = $errors;
    }

    header('Location: ' . base_url('views/profile.php'));
    exit;
}
?>
