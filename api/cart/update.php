<?php
// ============================================================
//  api/cart/update.php
//  POST /api/cart/update
//  Body: cart_id, quantity
//  Returns JSON with success, message, subtotal, item_count, total
// ============================================================

    session_start();
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        echo json_encode([
            'success'  => false,
            'message'  => 'Please log in.',
            'redirect' => '../../views/login.php'
        ]);
        exit;
    }

    require_once(__DIR__ . '/../../models/cartModel.php');

    $userId   = (int)$_SESSION['user_id'];
    $cartId   = (int)($_POST['cart_id']  ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 0);

    if ($cartId < 1) {
        echo json_encode(['success' => false, 'message' => 'Invalid cart item.']);
        exit;
    }

    $result = updateCartItem($cartId, $userId, $quantity);

    if ($result['success']) {
        // Re-fetch cart to get updated subtotal for this row
        $items = getCartByUser($userId);
        foreach ($items as $item) {
            if ($item['id'] == $cartId) {
                $result['subtotal']   = number_format($item['price'] * $item['quantity'], 2);
                $result['unit_price'] = number_format($item['price'], 2);
                break;
            }
        }

        $totals = getCartTotals($userId);
        $result['item_count'] = $totals['item_count'];
        $result['total']      = number_format($totals['total'], 2);
    }

    echo json_encode($result);
    exit;
?>
