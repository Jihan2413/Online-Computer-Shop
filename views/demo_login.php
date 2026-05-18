<!DOCTYPE html>
<html>
<head>
    <title>Login - PC Shop</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; }
        .container { width: 400px; margin: 100px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        select, button { width: 100%; padding: 10px; margin: 10px 0; font-size: 16px; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
        p { color: #777; font-size: 14px; text-align: center; }
    </style>
</head>
<body>
<div class="container">
    <h2>💻 PC Shop - Demo Login</h2>
    <p>Pick a role to test Task 4 features</p>

    <?php
    // Handle login form submit
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        session_start();
        $role = $_POST['role'];
        if ($role == 'admin') {
            $_SESSION['user_id'] = 1;
            $_SESSION['name']    = 'Admin User';
            $_SESSION['role']    = 'admin';
            header("Location: index.php?page=admin_dashboard");
        } else {
            $_SESSION['user_id'] = 2;
            $_SESSION['name']    = 'Test Customer';
            $_SESSION['role']    = 'customer';
            header("Location: index.php?page=checkout");
        }
        exit();
    }
    ?>

    <form method="POST" action="index.php?page=demo_login">
        <label>Login as:</label>
        <select name="role">
            <option value="customer">Customer</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit">Login</button>
    </form>

    <p>In real project, Task 1 handles login with real accounts.</p>
</div>
</body>
</html>
