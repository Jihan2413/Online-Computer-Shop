<?php
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