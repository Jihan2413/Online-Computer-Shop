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