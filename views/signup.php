<?php

    session_start();
    $pageTitle = 'Sign Up';

    $error = $_SESSION['error'] ?? '';
    unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up – PC Shop</title>
    <link rel="stylesheet" href="../public/assets/css/style.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-box">
        <h2>📝 Create Account</h2>

        <?php if ($error !== ''): ?>
            <p class="msg error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="post" action="../controllers/signupCheck.php">
            <label>Username</label>
            <input type="text" name="username" required autocomplete="username">

            <label>Email</label>
            <input type="email" name="email" required autocomplete="email">

            <label>Password <small>(min 6 characters)</small></label>
            <input type="password" name="password" required autocomplete="new-password">

            <button type="submit" name="submit" class="btn-primary">Create Account</button>
        </form>

        <p class="auth-link">Already have an account? <a href="login.php">Login here</a></p>
        <p class="auth-link"><a href="../index.php">← Back to Shop</a></p>
    </div>
</div>
</body>
</html>
