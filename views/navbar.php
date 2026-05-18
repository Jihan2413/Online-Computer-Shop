<!-- navbar.php - include this at top of every view -->
<style>
    body { font-family: Arial, sans-serif; margin: 0; background: #f5f5f5; }
    .navbar { background: #007bff; color: white; padding: 12px 20px; display: flex; align-items: center; gap: 15px; }
    .navbar a { color: white; text-decoration: none; font-size: 15px; }
    .navbar a:hover { text-decoration: underline; }
    .navbar .brand { font-size: 18px; font-weight: bold; margin-right: auto; }
    .page-content { max-width: 1000px; margin: 30px auto; padding: 0 15px; }
    .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px 12px; border: 1px solid #ddd; text-align: left; }
    th { background: #f0f0f0; }
    .btn { padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; }
    .btn-primary { background: #007bff; color: white; }
    .btn-danger  { background: #dc3545; color: white; }
    .btn-success { background: #28a745; color: white; }
    .btn:hover { opacity: 0.85; }
    .alert { padding: 12px 15px; border-radius: 5px; margin-bottom: 15px; }
    .alert-danger  { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    input, select, textarea { width: 100%; padding: 8px; margin: 5px 0 12px 0; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
    label { font-weight: bold; font-size: 14px; }
</style>

<div class="navbar">
    <span class="brand">💻 PC Shop</span>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
        <a href="index.php?page=admin_dashboard">Dashboard</a>
        <a href="index.php?page=admin_customers">Customers</a>
        <a href="index.php?page=admin_reviews">Reviews</a>
    <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'customer'): ?>
        <a href="index.php?page=checkout">🛒 Checkout</a>
        <a href="index.php?page=my_orders">My Orders</a>
    <?php endif; ?>
    <?php if (isset($_SESSION['name'])): ?>
        <span>👤 <?php echo htmlspecialchars($_SESSION['name']); ?></span>
        <a href="index.php?page=logout">Logout</a>
    <?php endif; ?>
</div>
