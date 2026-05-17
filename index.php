<?php

session_start();

$page = isset($_GET['page']) && $_GET['page'] !== '' ? trim($_GET['page']) : 'home';

if ($page == 'checkout') {
    include 'controllers/OrderController.php';
} elseif ($page == 'order_confirm') {
    include 'controllers/OrderController.php';
} elseif ($page == 'my_orders') {
    include 'controllers/OrderController.php';
} elseif ($page == 'add_review') {
    include 'controllers/ReviewController.php';
} elseif ($page == 'delete_review') {
    include 'controllers/ReviewController.php';
} elseif ($page == 'admin_customers') {
    include 'controllers/AdminController.php';
} elseif ($page == 'admin_reviews') {
    include 'controllers/AdminController.php';
} elseif ($page == 'admin_dashboard') {
    include 'controllers/AdminController.php';
} elseif ($page == 'demo_login') {
    include 'views/demo_login.php';
} elseif ($page == 'logout') {
    session_destroy();
    header("Location: index.php?page=demo_login");
    exit();
} else {
    include 'views/demo_login.php';
}
?>

