<?php

    session_start();
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Please log in to add items to your cart.',
            'redirect' => '../../views/login.php'
        ]);
        exit;
    }

    require_once('/../../models/cartModel.php');

    $userId    = (int)$_SESSION['user_id'];
    $productId = (int)($_POST['product_id'] ?? 0);
    $quantity  = (int)($_POST['quantity']   ?? 1);

    if ($productId < 1) {
        echo json_encode(['success' => false, 'message' => 'Invalid product.']);
        exit;
    }

    $result = addToCart($userId, $productId, $quantity);

    if ($result['success']) {
        $totals = getCartTotals($userId);
        $result['item_count'] = $totals['item_count'];
        $result['total']      = number_format($totals['total'], 2);
    }

    echo json_encode($result);
    exit;
?>
