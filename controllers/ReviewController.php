<?php
session_start();

require_once 'config/database.php';
require_once 'models/Review.php';

$conn = getConnection(); // MUST

// Ensure $page exists safely
$page = isset($_GET['page']) ? $_GET['page'] : '';

// -------------------- ADD REVIEW --------------------
if ($page == 'add_review' && $_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
        echo json_encode(['error' => 'You must be logged in as a customer.']);
        exit();
    }

    $product_id    = $_POST['product_id'];
    $comment       = trim($_POST['comment']);
    $reviewer_name = $_SESSION['name'];
    $user_id       = $_SESSION['user_id'];

    // Validation
    $errors = [];

    if (empty($comment)) {
        $errors[] = "Comment cannot be empty.";
    }

    if (strlen($comment) > 1000) {
        $errors[] = "Comment is too long (max 1000 characters).";
    }

    if (!empty($errors)) {
        echo json_encode(['error' => implode(' ', $errors)]);
        exit();
    }

    // Insert review
    $new_id = addReview($conn, $product_id, $user_id, $reviewer_name, $comment);

    echo json_encode([
        'success'       => true,
        'id'            => $new_id,
        'reviewer_name' => htmlspecialchars($reviewer_name),
        'comment'       => htmlspecialchars($comment),
        'created_at'    => date('Y-m-d H:i:s'),
        'user_id'       => $user_id
    ]);
    exit();
}


// -------------------- DELETE REVIEW --------------------
if ($page == 'delete_review' && $_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'Not logged in.']);
        exit();
    }

    $review_id = $_POST['review_id'];

    $review = getReviewById($conn, $review_id);

    if (!$review) {
        echo json_encode(['error' => 'Review not found.']);
        exit();
    }

    // Only admin or owner can delete
    if ($_SESSION['role'] == 'admin' || $review['user_id'] == $_SESSION['user_id']) {

        deleteReview($conn, $review_id);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'You cannot delete this review.']);
    }

    exit();
}
?>