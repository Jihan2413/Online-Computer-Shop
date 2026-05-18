<?php


    require_once('/../config/db.php');

   
    function _productBaseSelect() {
        return "SELECT p.*, c.name AS category_name, c.slug AS category_slug,
                       b.name AS brand_name,    b.slug AS brand_slug
                FROM   products p
                JOIN   categories c ON p.category_id = c.id
                JOIN   brands     b ON p.brand_id    = b.id";
    }

   
    function getAllProducts($limit = 20) {
        $con  = getConnection();
        $sql  = _productBaseSelect() . " ORDER BY p.name LIMIT ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }

        mysqli_close($con);
        return $products;
    }

    
    function getProductsByCategoryIds($ids) {
        if (empty($ids)) return [];

        $con         = getConnection();
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql          = _productBaseSelect()
                      . " WHERE p.category_id IN ($placeholders) ORDER BY p.name";

        $stmt  = mysqli_prepare($con, $sql);
        $types = str_repeat('i', count($ids));
        mysqli_stmt_bind_param($stmt, $types, ...$ids);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }

        mysqli_close($con);
        return $products;
    }

    
    function getProductsByBrand($brandId) {
        $con  = getConnection();
        $sql  = _productBaseSelect() . " WHERE p.brand_id = ? ORDER BY p.name";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $brandId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }

        mysqli_close($con);
        return $products;
    }

    
    function getProductById($id) {
        $con  = getConnection();
        $sql  = _productBaseSelect() . " WHERE p.id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row    = mysqli_fetch_assoc($result);
        mysqli_close($con);
        return $row;
    }

  
    function searchProducts($q) {
        $con     = getConnection();
        $keyword = '%' . $q . '%';
        $sql     = _productBaseSelect()
                 . " WHERE p.name LIKE ? OR p.description LIKE ? OR p.manufacturer_review LIKE ?
                    ORDER BY p.name";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $keyword, $keyword, $keyword);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }

        mysqli_close($con);
        return $products;
    }

    
    function filterProducts($minPrice, $maxPrice, $categoryId, $brandId) {
        $con    = getConnection();
        $sql    = _productBaseSelect() . " WHERE 1=1";
        $types  = "";
        $params = [];

        if ($minPrice !== null && $minPrice !== '') {
            $sql .= " AND p.price >= ?";
            $types .= "d";
            $params[] = (float)$minPrice;
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $sql .= " AND p.price <= ?";
            $types .= "d";
            $params[] = (float)$maxPrice;
        }

        if ($categoryId !== null && $categoryId !== '') {
            $sql .= " AND p.category_id = ?";
            $types .= "i";
            $params[] = (int)$categoryId;
        }

        if ($brandId !== null && $brandId !== '') {
            $sql .= " AND p.brand_id = ?";
            $types .= "i";
            $params[] = (int)$brandId;
        }

        $sql .= " ORDER BY p.price ASC";

        $stmt = mysqli_prepare($con, $sql);

        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }

        mysqli_close($con);
        return $products;
    }

   
    function getProductStock($productId) {
        $con  = getConnection();
        $sql  = "SELECT stock FROM products WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $productId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row    = mysqli_fetch_assoc($result);
        mysqli_close($con);
        return $row ? (int)$row['stock'] : 0;
    }
?>
