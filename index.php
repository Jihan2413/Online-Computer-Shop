<?php
<<<<<<< HEAD
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Computer Shop Management</title>

    <style>

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            font-family: Arial, sans-serif;
            background: #f2f2f2;
        }

        header{
            background: #1e3a8a;
            color: white;
            padding: 20px;
        }

        header h1{
            text-align: center;
        }

        nav{
            background: #111827;
            padding: 15px;
            text-align: center;
        }

        nav a{
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
        }

        nav a:hover{
            color: yellow;
        }

        .container{
            width: 90%;
            margin: 30px auto;
        }

        .hero{
            background: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
        }

        .hero h2{
            margin-bottom: 20px;
            color: #1e3a8a;
        }

        .hero p{
            font-size: 18px;
            color: #555;
        }

        .card-container{
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px,1fr));
            gap: 20px;
        }

        .card{
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            transition: 0.3s;
        }

        .card:hover{
            transform: translateY(-5px);
        }

        .card h3{
            margin-bottom: 15px;
            color: #1e3a8a;
        }

        .card a{
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background: #1e3a8a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .card a:hover{
            background: #2563eb;
        }

        footer{
            margin-top: 50px;
            background: #111827;
            color: white;
            text-align: center;
            padding: 20px;
        }

    </style>

</head>

<body>

<header>

    <h1>Computer Shop Management System</h1>

</header>

<nav>

    <a href="index.php">Home</a>

    <a href="views/dashboard.php">
        Dashboard
    </a>

    <a href="views/category.php">
        Category
    </a>

    <a href="views/product.php">
        Product
    </a>

</nav>

<div class="container">

    <div class="hero">

        <h2>Welcome to Admin Panel</h2>

        <p>
            Manage Categories, Brands and Products Easily
        </p>

    </div>

    <div class="card-container">

        <div class="card">

            <h3>Dashboard</h3>

            <p>
                View total products, categories and stock.
            </p>

            <a href="views/dashboard.php">
                Go Dashboard
            </a>

        </div>

        <div class="card">

            <h3>Category Management</h3>

            <p>
                Add, delete and manage categories.
            </p>

            <a href="views/category.php">
                Manage Category
            </a>

        </div>

        <div class="card">

            <h3>Product Management</h3>

            <p>
                Add products with image upload.
            </p>

            <a href="views/product.php">
                Manage Product
            </a>

        </div>

    </div>

</div>

<footer>

    <p>
        © 2026 Computer Shop Management System
    </p>

</footer>

</body>
</html>
=======
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/models/HomeModel.php';

$homeModel  = new HomeModel($conn);
$categories = $homeModel->getTopCategories();
$featured   = $homeModel->getFeaturedProducts();
$flashSuccess = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PCShop – Home</title>
    <link rel="stylesheet" href="<?= base_url('public/styles/navbar.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/styles/home.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/styles/footer.css') ?>" />
    <script src="https://kit.fontawesome.com/8f7b27f9d3.js" crossorigin="anonymous"></script>
    <script>window.APP_BASE_URL = '<?= BASE_URL ?>';</script>
</head>
<body>

<?php include __DIR__ . '/views/layouts/navbar.php'; ?>

<?php if ($flashSuccess): ?>
    <div class="flash-success"><?= htmlspecialchars($flashSuccess) ?></div>
<?php endif; ?>

<div class="category-bar">
    <ul>
        <?php foreach ($categories as $cat): ?>
            <li>
                    <a href="<?= base_url('views/category.php?id=' . $cat['id']) ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<section class="banner">
    <p class="banner-subtitle">BEST PRICES. BEST PARTS.</p>
    <h1 class="banner-title">
        Build Your Dream <span class="highlight">PC Setup</span> Today.
    </h1>
    <p class="banner-desc">Browse thousands of PC components, peripherals, and accessories with detailed specs and manufacturer reviews.</p>
    <a href="<?= base_url('views/category.php') ?>" class="banner-btn">Shop Now <i class="fas fa-arrow-right"></i></a>
</section>

<section class="featured-section">
    <div class="section-header">
        <div>
            <p class="section-sub">TOP PICKS</p>
            <h2>Featured Components</h2>
        </div>
    </div>

    <div class="products-grid">
        <?php if (empty($featured)): ?>
            <p class="no-products">No products available yet.</p>
        <?php else: ?>
            <?php foreach ($featured as $product): ?>
                <div class="product-card">
                    <a href="<?= base_url('views/product_detail.php?id=' . $product['id']) ?>">
                        <div class="product-img">
                            <?php if ($product['image_path']): ?>
                                <img src="public/<?= htmlspecialchars($product['image_path']) ?>"
                                     alt="<?= htmlspecialchars($product['name']) ?>" />
                            <?php else: ?>
                                <div class="img-placeholder">
                                    <i class="fas fa-microchip"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="product-review">
                                <?= htmlspecialchars(mb_strimwidth($product['manufacturer_review'] ?? '', 0, 80, '...')) ?>
                            </p>
                            <p class="product-price">$<?= number_format($product['price'], 2) ?></p>
                        </div>
                    </a>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'customer'): ?>
                        <button class="add-to-cart-btn"
                                data-id="<?= $product['id'] ?>"
                                onclick="addToCart(<?= $product['id'] ?>, this)">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    <?php else: ?>
                        <a href="<?= base_url('views/login.php') ?>" class="add-to-cart-btn login-to-buy">
                            <i class="fas fa-lock"></i> Login to Buy
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<section class="promo-section">
    <div class="promo-container">
        <div class="promo-text">
            <h2>New Arrivals Every Week!</h2>
            <p>Register today and get <strong>free shipping</strong> on your first order.</p>
            <a href="<?= base_url('views/register.php') ?>" class="promo-btn">Get Started <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="promo-badge">
            <span>FREE</span>
            <small>Shipping</small>
        </div>
    </div>
</section>

<?php include __DIR__ . '/views/layouts/footer.php'; ?>

<script src="<?= base_url('public/scripts/navbar.js') ?>"></script>
<script src="<?= base_url('public/scripts/cart.js') ?>"></script>
</body>
</html>
>>>>>>> 0ae91adb5eda840452e92f05810c7e2100dd1e06
