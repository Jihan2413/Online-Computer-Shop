<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($page)) {
    $page = isset($_GET['page']) && $_GET['page'] !== '' ? trim($_GET['page']) : '';
}

require_once 'config/database.php';
require_once 'models/Admin.php';
require_once 'models/Review.php';
require_once 'models/Order.php';

$conn = getConnection(); // 🔥 MUST

// ---------------- AUTH CHECK ----------------
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php?page=demo_login");
    exit();
}


// ---------------- DASHBOARD ----------------
if ($page == 'admin_dashboard') {

    $recent_orders  = getRecentOrders($conn);
    $recent_reviews = getAllReviews($conn);

    include 'views/admin_dashboard.php';
}


// ---------------- CUSTOMERS ----------------
if ($page == 'admin_customers') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user_id'])) {

        $del_id = $_POST['delete_user_id'];

        deleteReviewsByUser($conn, $del_id);
        deleteUserCart($conn, $del_id);
        deleteUser($conn, $del_id);

        header("Location: index.php?page=admin_customers&msg=deleted");
        exit();
    }

    $customers = getAllCustomers($conn);

    include 'views/admin_customers.php';
}


// ---------------- REVIEWS ----------------
if ($page == 'admin_reviews') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_review_id'])) {

        $del_id = $_POST['delete_review_id'];

        deleteReview($conn, $del_id);

        header("Location: index.php?page=admin_reviews&msg=deleted");
        exit();
    }

    $reviews = getAllReviews($conn);

    include 'views/admin_reviews.php';
}
?>