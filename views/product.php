<?php

require_once "../config/database.php";

$categories = $conn->query("SELECT * FROM categories");

?>

<!DOCTYPE html>
<html>
<head>

    <title>Product Management</title>

    <style>

        body{
            font-family: Arial;
            padding: 20px;
        }

        input, textarea, select{
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        button{
            padding: 10px 20px;
            background: blue;
            color: white;
            border: none;
        }

    </style>

</head>

<body>

<h2>Add Product</h2>

<form
    action="../../controllers/ProductController.php"
    method="POST"
    enctype="multipart/form-data"
>

<input type="text" name="name" placeholder="Product Name" required>

<textarea name="description" placeholder="Description"></textarea>

<textarea name="manufacturer_review" placeholder="Manufacturer Review"></textarea>

<input type="number" step="0.01" name="price" placeholder="Price" required>

<select name="category_id" id="category">

    <option value="">Select Category</option>

    <?php while($row = $categories->fetch_assoc()) { ?>

        <option value="<?= $row['id'] ?>">
            <?= $row['name'] ?>
        </option>

    <?php } ?>

</select>

<select name="brand_id" id="brand">

    <option value="">Select Brand</option>

</select>

<input type="file" name="image" required>

<input type="number" name="stock" placeholder="Stock Quantity">

<button type="submit" name="add">
    Add Product
</button>

</form>

<script>

document.getElementById("category").addEventListener("change", function(){

    let category_id = this.value;

    let xhr = new XMLHttpRequest();

    xhr.open(
        "GET",
        "../../controllers/getBrands.php?category_id=" + category_id,
        true
    );

    xhr.onload = function(){

        let brands = JSON.parse(this.responseText);

        let brandSelect = document.getElementById("brand");

        brandSelect.innerHTML =
            '<option value="">Select Brand</option>';

        brands.forEach(function(brand){

            brandSelect.innerHTML +=
                `<option value="${brand.id}">
                    ${brand.name}
                </option>`;
        });

    }

    xhr.send();

});

</script>

</body>
</html>