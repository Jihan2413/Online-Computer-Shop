<?php

    header('Content-Type: application/json');

    require_once('/../../models/productModel.php');

    $minPrice   = $_GET['min']         ?? null;
    $maxPrice   = $_GET['max']         ?? null;
    $categoryId = $_GET['category_id'] ?? null;
    $brandId    = $_GET['brand_id']    ?? null;

    if ($minPrice !== null && $minPrice !== '' && (!is_numeric($minPrice) || $minPrice < 0)) {
        echo json_encode(['success' => false, 'message' => 'Invalid min price.', 'products' => []]);
        exit;
    }

    if ($maxPrice !== null && $maxPrice !== '' && (!is_numeric($maxPrice) || $maxPrice < 0)) {
        echo json_encode(['success' => false, 'message' => 'Invalid max price.', 'products' => []]);
        exit;
    }

    if ($minPrice !== '' && $maxPrice !== '' && $minPrice !== null && $maxPrice !== null) {
        if ((float)$minPrice > (float)$maxPrice) {
            echo json_encode(['success' => false, 'message' => 'Min price cannot be greater than max price.', 'products' => []]);
            exit;
        }
    }

    $products = filterProducts($minPrice, $maxPrice, $categoryId, $brandId);

    $output = [];
    foreach ($products as $p) {
        $output[] = [
            'id'                  => (int)$p['id'],
            'name'                => $p['name'],
            'manufacturer_review' => $p['manufacturer_review'],
            'price'               => (float)$p['price'],
            'stock'               => (int)$p['stock'],
            'image'               => $p['image'],
            'category_name'       => $p['category_name'],
            'brand_name'          => $p['brand_name']
        ];
    }

    echo json_encode([
        'success'  => true,
        'count'    => count($output),
        'products' => $output
    ]);
    exit;
?>
