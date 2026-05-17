<?php

    session_start();
    $rootPath  = '../';
    $pageTitle = 'Login';

    $error   = $_SESSION['error']   ?? '';
    $success = $_SESSION['success'] ?? '';
    unset($_SESSION['error'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – PC Shop</title>
    <link rel="stylesheet" href="../public/assets/css/style.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-box">
        <h2>🔐 Login</h2>

        <?php if ($error !== ''): ?>
            <p class="msg error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if ($success !== ''): ?>
            <p class="msg success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form method="post" action="../controllers/loginCheck.php">
            <label>Username</label>
            <input type="text" name="username" required autocomplete="username">

            <label>Password</label>
            <input type="password" name="password" required autocomplete="current-password">

            <button type="submit" name="submit" class="btn-primary">Login</button>
        </form>

        <p class="auth-link">No account? <a href="signup.php">Sign up here</a></p>
        <p class="auth-link"><a href="../index.php">← Back to Shop</a></p>
    </div>
</div>
</body>
</html>
