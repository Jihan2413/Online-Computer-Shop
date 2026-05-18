<?php

    header('Content-Type: application/json');

    require_once('/../../models/productModel.php');

    $q = trim($_GET['q'] ?? '');

    if ($q === '') {
        echo json_encode(['success' => false, 'message' => 'Search query is empty.', 'products' => []]);
        exit;
    }

    if (strlen($q) < 2) {
        echo json_encode(['success' => false, 'message' => 'Please enter at least 2 characters.', 'products' => []]);
        exit;
    }

    $products = searchProducts($q);

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
