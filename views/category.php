<?php
session_start();

if($_SESSION['role'] != 'admin'){
    header("Location: ../../login.php");
}

require_once "../../config/database.php";
require_once "../../models/Category.php";

$category = new Category($conn);
$categories = $category->getAllCategories();
?>

<!DOCTYPE html>
<html>
<head>

    <title>Category Management</title>

    <style>

        body{
            font-family: Arial;
            background: #f2f2f2;
            padding: 20px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td{
            padding: 10px;
            border: 1px solid #ddd;
        }

        input, select{
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
        }

        button{
            padding: 10px 20px;
            background: green;
            color: white;
            border: none;
        }

    </style>

</head>

<body>

<h2>Category Management</h2>

<form action="../../controllers/CategoryController.php" method="POST">

    <input type="text" name="name" placeholder="Category Name" required>

    <select name="parent_id">

        <option value="">Main Category</option>

        <?php while($row = $categories->fetch_assoc()) { ?>

            <option value="<?= $row['id'] ?>">
                <?= $row['name'] ?>
            </option>

        <?php } ?>

    </select>

    <button type="submit" name="add">
        Add Category
    </button>

</form>

<hr>

<table>

    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Parent ID</th>
        <th>Action</th>
    </tr>

<?php

$categories = $category->getAllCategories();

while($row = $categories->fetch_assoc()){

?>

<tr>

    <td><?= $row['id'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= $row['parent_id'] ?></td>

    <td>

        <a href="../../controllers/CategoryController.php?delete=<?= $row['id'] ?>">
            Delete
        </a>

    </td>

</tr>

<?php } ?>

</table>

</body>
</html>
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/HomeModel.php';

$homeModel   = new HomeModel($conn);
$categories  = $homeModel->getTopCategories();
$categoryId  = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$category    = $categoryId ? $homeModel->getCategoryById($categoryId) : null;
$products    = $category ? $homeModel->getProductsByCategoryId($categoryId) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($category['name'] ?? 'Categories') ?> | PCShop</title>
    <link rel="stylesheet" href="<?= base_url('public/styles/navbar.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/styles/home.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/styles/footer.css') ?>" />
    <script src="https://kit.fontawesome.com/8f7b27f9d3.js" crossorigin="anonymous"></script>
    <script>window.APP_BASE_URL = '<?= BASE_URL ?>';</script>
</head>
<body>

<?php include __DIR__ . '/layouts/navbar.php'; ?>

<div class="category-page">
    <div class="category-bar">
        <ul>
            <?php foreach ($categories as $cat): ?>
                <li>
                    <a href="<?= base_url('views/category.php?id=' . $cat['id']) ?>"
                       class="<?= $category && $category['id'] === $cat['id'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <section class="featured-section">
        <div class="section-header">
            <div>
                <p class="section-sub">CATEGORY</p>
                <h2><?= htmlspecialchars($category['name'] ?? 'Category not found') ?></h2>
            </div>
        </div>

        <?php if (!$category): ?>
            <div class="products-grid">
                <p class="no-products">The category does not exist. Please choose a different category from the bar above.</p>
            </div>
        <?php elseif (empty($products)): ?>
            <div class="products-grid">
                <p class="no-products">No products found in this category yet.</p>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
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
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>

<script src="<?= base_url('public/scripts/navbar.js') ?>"></script>
</body>
</html>
