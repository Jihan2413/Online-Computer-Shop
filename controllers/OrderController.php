<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($page)) {
    $page = isset($_GET['page']) && $_GET['page'] !== '' ? trim($_GET['page']) : '';
}
require_once 'models/Order.php';

// Check login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: index.php?page=demo_login");
    exit();
}

$user_id = $_SESSION['user_id'];

// ---- CHECKOUT PAGE ----
if ($page == 'checkout') {
    $cart_items = getCartItems($user_id);
    $total = 0;
    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    include 'views/checkout.php';
}

// ---- PLACE ORDER ----
if ($page == 'checkout' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_method = $_POST['payment_method'];
    $cart_items     = getCartItems($user_id);

    // PHP Validation
    $errors = [];

    if (empty($cart_items)) {
        $errors[] = "Your cart is empty.";
    }

    if (empty($payment_method)) {
        $errors[] = "Please select a payment method.";
    }

    $allowed_methods = ['cash_on_delivery', 'online_wallet'];
    if (!in_array($payment_method, $allowed_methods)) {
        $errors[] = "Invalid payment method.";
    }

    if (!empty($errors)) {
        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        include 'views/checkout.php';
        exit();
    }

    // Calculate total
    $total = 0;
    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Create order
    $order_id = createOrder($user_id, $total, $payment_method);

    // Add each cart item to order_items
    foreach ($cart_items as $item) {
        addOrderItem($order_id, $item['product_id'], $item['quantity'], $item['price']);
    }

    // Clear the cart
    clearCart($user_id);

    // Go to confirmation page
    header("Location: index.php?page=order_confirm&order_id=$order_id");
    exit();
}

// ---- ORDER CONFIRMATION PAGE ----
if ($page == 'order_confirm') {
    $order_id   = $_GET['order_id'];
    $order      = getOrderById($order_id);
    $order_items = getOrderItems($order_id);

    // Make sure this order belongs to this user
    if (!$order || $order['user_id'] != $user_id) {
        die("Access denied.");
    }

    include 'views/order_confirm.php';
}

// ---- MY ORDERS PAGE ----
if ($page == 'my_orders') {
    $orders = getMyOrders($user_id);
    include 'views/my_orders.php';
}
?>
