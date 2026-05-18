<?php

    require_once(__DIR__ . '/../../models/categoryModel.php');

    $allCategories = getAllCategories();
    $allBrands     = getAllBrands();
    $loggedIn      = isset($_SESSION['user_id']);
    $username      = $loggedIn ? htmlspecialchars($_SESSION['username']) : '';

   
    $cartCount = 0;
    if ($loggedIn) {
        require_once(__DIR__ . '/../../models/cartModel.php');
        $totals    = getCartTotals($_SESSION['user_id']);
        $cartCount = $totals['item_count'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' – ' : ''; ?>PC Shop</title>
    <link rel="stylesheet" href="<?php echo $rootPath ?? ''; ?>public/assets/css/style.css">
</head>
<body>


<header class="site-header">
    <div class="header-inner">
        <a href="<?php echo $rootPath ?? ''; ?>index.php" class="logo">💻 PC Shop</a>

        
        <div class="search-wrap">
            <input type="text" id="searchInput" placeholder="Search products…" autocomplete="off">
            <button onclick="doSearch()">Search</button>
        </div>

        
        <nav class="top-links">
            <?php if ($loggedIn): ?>
                <span>Hi, <?php echo $username; ?></span>
                <a href="<?php echo $rootPath ?? ''; ?>cart.php" class="cart-link">
                    🛒 Cart
                    <?php if ($cartCount > 0): ?>
                        <span class="cart-badge"><?php echo $cartCount; ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?php echo $rootPath ?? ''; ?>controllers/logoutCheck.php">Logout</a>
            <?php else: ?>
                <a href="<?php echo $rootPath ?? ''; ?>views/login.php">Login</a>
                <a href="<?php echo $rootPath ?? ''; ?>views/signup.php">Sign Up</a>
                <a href="<?php echo $rootPath ?? ''; ?>cart.php" class="cart-link">🛒 Cart</a>
            <?php endif; ?>
        </nav>
    </div>
</header>


<nav class="cat-nav">
    <ul class="cat-list">
        <li><a href="<?php echo $rootPath ?? ''; ?>index.php">All Products</a></li>
        <?php foreach ($allCategories as $cat): ?>
            <li class="has-dropdown">
                <a href="<?php echo $rootPath ?? ''; ?>category.php?slug=<?php echo urlencode($cat['slug']); ?>">
                    <?php echo htmlspecialchars($cat['name']); ?>
                </a>
                <?php if (!empty($cat['children'])): ?>
                    <ul class="dropdown">
                        <?php foreach ($cat['children'] as $sub): ?>
                            <li>
                                <a href="<?php echo $rootPath ?? ''; ?>category.php?slug=<?php echo urlencode($sub['slug']); ?>">
                                    <?php echo htmlspecialchars($sub['name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>

       
        <li class="has-dropdown">
            <a href="#">Brands</a>
            <ul class="dropdown">
                <?php foreach ($allBrands as $brand): ?>
                    <li>
                        <a href="<?php echo $rootPath ?? ''; ?>brand.php?slug=<?php echo urlencode($brand['slug']); ?>">
                            <?php echo htmlspecialchars($brand['name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>
</nav>

<div id="searchOverlay" class="search-overlay" style="display:none;">
    <div class="search-overlay-inner">
        <button class="close-search" onclick="closeSearch()">✕ Close</button>
        <h3>Search Results</h3>
        <div id="searchResults" class="product-grid"></div>
    </div>
</div>


<div class="page-wrap">
