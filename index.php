<?php

    session_start();
    $rootPath  = '';
    $pageTitle = 'Home';

    require_once('models/productModel.php');
    require_once('models/categoryModel.php');

    $products = getAllProducts(50);

    include('views/layout/header.php');
?>

<div class="content-wrap">

    <aside class="filter-sidebar">
        <h3>Filter Products</h3>

        <label>Min Price (৳)</label>
        <input type="number" id="minPrice" placeholder="0" min="0">

        <label>Max Price (৳)</label>
        <input type="number" id="maxPrice" placeholder="Any" min="0">

        <label>Category</label>
        <select id="filterCategory">
            <option value="">All Categories</option>
            <?php
            $cats = getAllCategories();
            foreach ($cats as $cat):
            ?>
                <optgroup label="<?php echo htmlspecialchars($cat['name']); ?>">
                    <?php foreach ($cat['children'] as $sub): ?>
                        <option value="<?php echo (int)$sub['id']; ?>">
                            &nbsp;&nbsp;<?php echo htmlspecialchars($sub['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </optgroup>
            <?php endforeach; ?>
        </select>

        <label>Brand</label>
        <select id="filterBrand">
            <option value="">All Brands</option>
            <?php
            $brands = getAllBrands();
            foreach ($brands as $brand):
            ?>
                <option value="<?php echo (int)$brand['id']; ?>">
                    <?php echo htmlspecialchars($brand['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button class="btn-primary" onclick="doFilter()">Apply Filter</button>
        <button class="btn-secondary" onclick="resetFilter()">Reset</button>

        <p id="filterMsg" class="msg" style="display:none;"></p>
    </aside>

    <main class="listing-main">
        <h2>All Products <span class="count" id="productCount">(<?php echo count($products); ?>)</span></h2>

        <div class="product-grid" id="productGrid">
            <?php foreach ($products as $product): ?>
                <?php include('views/layout/productCard.php'); ?>
            <?php endforeach; ?>

            <?php if (empty($products)): ?>
                <p class="empty-msg">No products available yet.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php include('views/layout/footer.php'); ?>
session_start();

$page = isset($_GET['page']) && $_GET['page'] !== '' ? trim($_GET['page']) : 'home';

if ($page == 'checkout') {
    include 'controllers/OrderController.php';
} elseif ($page == 'order_confirm') {
    include 'controllers/OrderController.php';
} elseif ($page == 'my_orders') {
    include 'controllers/OrderController.php';
} elseif ($page == 'add_review') {
    include 'controllers/ReviewController.php';
} elseif ($page == 'delete_review') {
    include 'controllers/ReviewController.php';
} elseif ($page == 'admin_customers') {
    include 'controllers/AdminController.php';
} elseif ($page == 'admin_reviews') {
    include 'controllers/AdminController.php';
} elseif ($page == 'admin_dashboard') {
    include 'controllers/AdminController.php';
} elseif ($page == 'demo_login') {
    include 'views/demo_login.php';
} elseif ($page == 'logout') {
    session_destroy();
    header("Location: index.php?page=demo_login");
    exit();
} else {
    include 'views/demo_login.php';
}
?>

