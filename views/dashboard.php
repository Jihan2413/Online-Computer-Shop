<?php

require_once "../../config/database.php";

$total_products =
$conn->query("SELECT * FROM products")->num_rows;

$total_categories =
$conn->query("SELECT * FROM categories")->num_rows;

$total_brands =
$conn->query("SELECT * FROM brands")->num_rows;

$low_stock =
$conn->query("SELECT * FROM products WHERE stock < 5");

?>

<!DOCTYPE html>
<html>
<head>

    <title>Admin Dashboard</title>

    <style>

        body{
            font-family: Arial;
            background: #f2f2f2;
            padding: 20px;
        }

        .card{
            background: white;
            padding: 20px;
            margin-bottom: 20px;
        }

    </style>

</head>

<body>

<h1>Admin Dashboard</h1>

<div class="card">
    <h2>Total Products:
        <?= $total_products ?>
    </h2>
</div>

<div class="card">
    <h2>Total Categories:
        <?= $total_categories ?>
    </h2>
</div>

<div class="card">
    <h2>Total Brands:
        <?= $total_brands ?>
    </h2>
</div>

<div class="card">

<h2>Low Stock Products</h2>

<?php while($row = $low_stock->fetch_assoc()) { ?>

<p>
    <?= $row['name'] ?>
    (<?= $row['stock'] ?> left)
</p>

<?php } ?>

</div>

</body>
</html>