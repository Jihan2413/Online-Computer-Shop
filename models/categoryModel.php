<?php
    require_once('/../config/db.php');

    function getAllCategories() {
        $con = getConnection();
        $sql = "SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $categories = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $row['children'] = getSubCategories($row['id']);
            $categories[] = $row;
        }

        mysqli_close($con);
        return $categories;
    }

    function getSubCategories($parentId) {
        $con = getConnection();
        $sql = "SELECT * FROM categories WHERE parent_id = ? ORDER BY name";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $parentId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $subs = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $subs[] = $row;
        }

        mysqli_close($con);
        return $subs;
    }

    function getCategoryBySlug($slug) {
        $con = getConnection();
        $sql = "SELECT * FROM categories WHERE slug = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $slug);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_close($con);
        return $row;
    }

    function getCategoryIdTree($categoryId) {
        $ids = [$categoryId];
        $children = getSubCategories($categoryId);
        foreach ($children as $child) {
            $ids[] = $child['id'];
            // go one more level deep if needed
            $grandchildren = getSubCategories($child['id']);
            foreach ($grandchildren as $gc) {
                $ids[] = $gc['id'];
            }
        }
        return $ids;
    }

    function getAllBrands() {
        $con = getConnection();
        $sql = "SELECT * FROM brands ORDER BY name";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $brands = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $brands[] = $row;
        }

        mysqli_close($con);
        return $brands;
    }

    function getBrandBySlug($slug) {
        $con = getConnection();
        $sql = "SELECT * FROM brands WHERE slug = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $slug);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_close($con);
        return $row;
    }
?>
